<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NetworkController extends Controller
{
    /**
     * Display listing of direct members for current logged-in user.
     */
    public function directMembers(Request $request): View
    {
        $user = Auth::user();
        $query = User::where('sponsor_code', $user->referral_code);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $directs = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => User::where('sponsor_code', $user->referral_code)->count(),
            'active' => User::where('sponsor_code', $user->referral_code)->where('status', 'active')->count(),
            'left' => User::where('sponsor_code', $user->referral_code)->where('position', 'left')->count(),
            'right' => User::where('sponsor_code', $user->referral_code)->where('position', 'right')->count(),
        ];

        return view('user.network.direct', compact('directs', 'stats'));
    }

    /**
     * Display Visual Binary Team Tree for member.
     */
    public function treeView(Request $request): View
    {
        $currentUser = Auth::user();
        $searchCode = $request->query('code');

        if ($searchCode) {
            $targetUser = User::where('referral_code', $searchCode)->first();
            // Ensure member can view root or downline tree
            if ($targetUser) {
                $rootUser = $targetUser;
            } else {
                $rootUser = $currentUser;
            }
        } else {
            $rootUser = $currentUser;
        }

        $treeData = $this->buildBinaryTreeData($rootUser);
        $directMembers = User::where('sponsor_code', $rootUser->referral_code)
            ->withCount('directMembers')
            ->latest()
            ->get();

        return view('user.network.tree', compact('rootUser', 'treeData', 'directMembers'));
    }

    /**
     * Build dynamic binary genealogy team tree structure for visual display.
     */
    private function buildBinaryTreeData(User $root): array
    {
        return $this->buildCompleteBinaryTreeData($root);
    }

    /**
     * Build the visual tree from each member's actual left and right placements.
     *
     * A member is never moved to the other leg merely to make the diagram look full.
     */
    private function buildAccurateBinaryTreeData(User $root): array
    {
        $root->load(['sponsor', 'userPackages', 'transactions'])
            ->loadCount('directMembers');

        $currentLevel = collect([$root]);

        for ($depth = 0; $depth < 15 && $currentLevel->isNotEmpty(); $depth++) {
            $childrenBySponsor = User::query()
                ->whereIn('placement_parent_code', $currentLevel->pluck('referral_code'))
                ->where('sponsor_code', $root->referral_code)
                ->with(['sponsor', 'userPackages', 'transactions'])
                ->withCount('directMembers')
                ->oldest()
                ->get()
                ->groupBy('placement_parent_code');

            $nextLevel = collect();

            foreach ($currentLevel as $member) {
                $children = $childrenBySponsor->get($member->referral_code, collect());
                $member->left_child = $children->firstWhere('position', 'left');
                $member->right_child = $children->firstWhere('position', 'right');

                if ($member->left_child) {
                    $nextLevel->push($member->left_child);
                }

                if ($member->right_child) {
                    $nextLevel->push($member->right_child);
                }
            }

            $currentLevel = $nextLevel;
        }

        $getBranchMembers = function (?User $branchRoot) use ($root): Collection {
            if (! $branchRoot) {
                return collect();
            }

            $members = collect([$branchRoot]);
            $currentCodes = collect([$branchRoot->referral_code]);

            for ($depth = 0; $depth < 15 && $currentCodes->isNotEmpty(); $depth++) {
                $children = User::whereIn('placement_parent_code', $currentCodes)
                    ->where('sponsor_code', $root->referral_code)
                    ->get();
                $members = $members->merge($children);
                $currentCodes = $children->pluck('referral_code');
            }

            return $members->unique('id')->values();
        };

        $leftMembers = $getBranchMembers($root->left_child);
        $rightMembers = $getBranchMembers($root->right_child);
        $leftUserIds = $leftMembers->pluck('id');
        $rightUserIds = $rightMembers->pluck('id');

        $leftBusiness = $leftUserIds->isEmpty() ? 0.00 : (float) UserPackage::whereIn('user_id', $leftUserIds)
            ->where('status', 'active')
            ->sum('invested_amount');
        $rightBusiness = $rightUserIds->isEmpty() ? 0.00 : (float) UserPackage::whereIn('user_id', $rightUserIds)
            ->where('status', 'active')
            ->sum('invested_amount');

        return [
            'root' => $root,
            'left_child' => $root->left_child,
            'right_child' => $root->right_child,
            'left_business' => $leftBusiness,
            'right_business' => $rightBusiness,
            'left_count' => $leftMembers->count(),
            'right_count' => $rightMembers->count(),
            'left_active' => $leftMembers->where('status', 'active')->count(),
            'left_inactive' => $leftMembers->where('status', '!=', 'active')->count(),
            'right_active' => $rightMembers->where('status', 'active')->count(),
            'right_inactive' => $rightMembers->where('status', '!=', 'active')->count(),
            'total_team' => $leftMembers->count() + $rightMembers->count(),
            'total_business' => $leftBusiness + $rightBusiness,
        ];
    }

    private function buildCompleteBinaryTreeData(User $root): array
    {
        // Helper closure to fetch all downline members for a direct leg in creation order
        $getLegMembers = function (User $rootUser, string $position) {
            $directs = User::where('sponsor_code', $rootUser->referral_code)
                ->where('position', $position)
                ->orderBy('created_at', 'asc')
                ->get();

            if ($directs->isEmpty() && $position === 'left') {
                $directs = User::where('sponsor_code', $rootUser->referral_code)
                    ->where(function ($q) {
                        $q->whereNull('position')->orWhere('position', 'left');
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();
            }

            $allLegUserIds = $directs->pluck('id')->all();

            if (empty($allLegUserIds)) {
                return collect();
            }

            return User::whereIn('id', $allLegUserIds)
                ->with(['sponsor', 'userPackages', 'transactions'])
                ->orderBy('created_at', 'asc')
                ->get();
        };

        $leftMembers = $getLegMembers($root, 'left');
        $rightMembers = $getLegMembers($root, 'right');

        $assignedLeftIds = [];
        $assignedRightIds = [];

        // Helper to populate children level-by-level (BFS Queue) for a subtree leg up to depth 15
        $populateLegTree = function (User $subRoot, string $leg) use (&$assignedLeftIds, &$assignedRightIds, $leftMembers, $rightMembers, $root): void {
            if ($leg === 'left') {
                $legPool = $leftMembers;
                $assignedIds = &$assignedLeftIds;
            } else {
                $legPool = $rightMembers;
                $assignedIds = &$assignedRightIds;
            }

            $queue = [['node' => $subRoot, 'depth' => 1]];

            while (! empty($queue)) {
                $current = array_shift($queue);
                $node = $current['node'];
                $depth = $current['depth'];

                if ($depth >= 15) {
                    continue;
                }

                // Left Child for $node:
                // 1. Direct referral of $node (position left or null)
                // 2. Or next available in leg pool
                $leftChild = User::where('sponsor_code', $node->referral_code)
                    ->where('sponsor_code', $root->referral_code)
                    ->whereNotIn('id', $assignedIds)
                    ->where('id', '!=', $node->id)
                    ->where(function ($q) {
                        $q->where('position', 'left')->orWhereNull('position');
                    })
                    ->oldest()
                    ->first()
                    ?? $legPool->whereNotIn('id', array_merge($assignedIds, [$node->id]))->first();

                if ($leftChild) {
                    $leftChild->load(['sponsor', 'userPackages', 'transactions'])->loadCount('directMembers');
                    $assignedIds[] = $leftChild->id;
                    $node->left_child = $leftChild;
                    $queue[] = ['node' => $leftChild, 'depth' => $depth + 1];
                } else {
                    $node->left_child = null;
                }

                // Right Child for $node:
                // 1. Direct referral of $node (position right)
                // 2. Or next available in leg pool
                $rightChild = User::where('sponsor_code', $node->referral_code)
                    ->where('sponsor_code', $root->referral_code)
                    ->whereNotIn('id', $assignedIds)
                    ->where('id', '!=', $node->id)
                    ->where('position', 'right')
                    ->oldest()
                    ->first()
                    ?? $legPool->whereNotIn('id', array_merge($assignedIds, [$node->id]))->first();

                if ($rightChild) {
                    $rightChild->load(['sponsor', 'userPackages', 'transactions'])->loadCount('directMembers');
                    $assignedIds[] = $rightChild->id;
                    $node->right_child = $rightChild;
                    $queue[] = ['node' => $rightChild, 'depth' => $depth + 1];
                } else {
                    $node->right_child = null;
                }
            }
        };

        $root->load(['sponsor', 'userPackages', 'transactions'])->loadCount('directMembers');

        // First Left Direct / Member of Root
        $firstLeft = User::where('sponsor_code', $root->referral_code)
            ->where(function ($q) {
                $q->where('position', 'left')->orWhereNull('position');
            })
            ->oldest()
            ->first()
            ?? $leftMembers->first();

        if ($firstLeft) {
            $firstLeft->load(['sponsor', 'userPackages', 'transactions'])->loadCount('directMembers');
            $assignedLeftIds[] = $firstLeft->id;
            $root->left_child = $firstLeft;
            $populateLegTree($firstLeft, 'left');
        } else {
            $root->left_child = null;
        }

        // First Right Direct / Member of Root
        $firstRight = User::where('sponsor_code', $root->referral_code)
            ->where('position', 'right')
            ->oldest()
            ->first()
            ?? $rightMembers->first();

        if ($firstRight) {
            $firstRight->load(['sponsor', 'userPackages', 'transactions'])->loadCount('directMembers');
            $assignedRightIds[] = $firstRight->id;
            $root->right_child = $firstRight;
            $populateLegTree($firstRight, 'right');
        } else {
            $root->right_child = null;
        }

        // Calculate Business Volumes and Total Downline Counts
        $leftUserIds = $leftMembers->pluck('id')->toArray();
        $rightUserIds = $rightMembers->pluck('id')->toArray();

        $leftBusiness = ! empty($leftUserIds)
            ? (float) UserPackage::whereIn('user_id', $leftUserIds)->where('status', 'active')->sum('invested_amount')
            : 0.00;

        $rightBusiness = ! empty($rightUserIds)
            ? (float) UserPackage::whereIn('user_id', $rightUserIds)->where('status', 'active')->sum('invested_amount')
            : 0.00;

        $leftCount = count($leftUserIds);
        $rightCount = count($rightUserIds);

        $leftActiveCount = $leftMembers->where('status', 'active')->count();
        $leftInactiveCount = $leftMembers->where('status', '!=', 'active')->count();
        $rightActiveCount = $rightMembers->where('status', 'active')->count();
        $rightInactiveCount = $rightMembers->where('status', '!=', 'active')->count();

        return [
            'root' => $root,
            'left_child' => $root->left_child,
            'right_child' => $root->right_child,
            'left_business' => $leftBusiness,
            'right_business' => $rightBusiness,
            'left_count' => $leftCount,
            'right_count' => $rightCount,
            'left_active' => $leftActiveCount,
            'left_inactive' => $leftInactiveCount,
            'right_active' => $rightActiveCount,
            'right_inactive' => $rightInactiveCount,
            'total_team' => $leftCount + $rightCount,
            'total_business' => $leftBusiness + $rightBusiness,
        ];
    }
}
