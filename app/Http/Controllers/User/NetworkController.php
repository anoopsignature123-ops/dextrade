<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Http\JsonResponse;
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
    public function treeView(Request $request): View|JsonResponse
    {
        $currentUser = Auth::user();
        $search = $request->query('code') ?? $request->query('id') ?? $request->query('search');

        $rootUser = $currentUser;

        if ($search) {
            $targetUser = User::where('referral_code', $search)
                ->orWhere('id', $search)
                ->orWhere('name', 'like', "%{$search}%")
                ->first();

            if ($targetUser && $this->isDownline($currentUser, $targetUser)) {
                $rootUser = $targetUser;
            }
        }

        $treeData = $this->buildBinaryTreeData($rootUser);
        $directMembers = User::where('sponsor_code', $rootUser->referral_code)
            ->withCount('directMembers')
            ->latest()
            ->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'user' => $rootUser,
                'treeData' => $treeData,
                'directMembers' => $directMembers,
            ]);
        }

        return view('user.network.tree', compact('rootUser', 'treeData', 'directMembers'));
    }

    /**
     * Check if a target user is in the authenticated user's downline structure.
     */
    private function isDownline(User $authUser, User $targetUser): bool
    {
        if ($authUser->id === $targetUser->id) {
            return true;
        }

        $visited = [$targetUser->id => true];
        $current = $targetUser;

        while ($current) {
            $parentCode = $current->placement_parent_code ?? $current->sponsor_code;
            if (! $parentCode) {
                break;
            }

            if ($parentCode === $authUser->referral_code) {
                return true;
            }

            $parent = User::where('referral_code', $parentCode)->first();
            if (! $parent || isset($visited[$parent->id])) {
                break;
            }

            if ($parent->id === $authUser->id) {
                return true;
            }

            $visited[$parent->id] = true;
            $current = $parent;
        }

        return false;
    }

    /**
     * Build dynamic binary genealogy team tree structure for visual display.
     */
    private function buildBinaryTreeData(User $root): array
    {
        $root->load(['sponsor', 'userPackages', 'transactions'])
            ->loadCount('directMembers');

        $assignedIds = [$root->id => true];
        $membersByCode = collect([$root])->keyBy('referral_code');
        $currentLevel = collect([$root]);

        while ($currentLevel->isNotEmpty()) {
            $nextLevel = collect();

            foreach ($currentLevel as $member) {
                $member->left_child = null;
                $member->right_child = null;
            }

            $parentCodes = $currentLevel->pluck('referral_code');
            $children = User::query()
                ->where(function ($query) use ($parentCodes) {
                    $query->whereIn('placement_parent_code', $parentCodes)
                        ->orWhere(function ($query) use ($parentCodes) {
                            $query->whereNull('placement_parent_code')
                                ->whereIn('sponsor_code', $parentCodes);
                        });
                })
                ->whereNotIn('id', array_keys($assignedIds))
                ->with(['sponsor', 'userPackages', 'transactions'])
                ->withCount('directMembers')
                ->oldest()
                ->get();

            foreach ($children as $child) {
                $parentCode = $child->placement_parent_code ?? $child->sponsor_code;
                $parent = $membersByCode->get($parentCode);
                $childAttribute = $child->position.'_child';

                if (! $parent || ! in_array($child->position, ['left', 'right'], true) || $parent->{$childAttribute}) {
                    continue;
                }

                $parent->{$childAttribute} = $child;
                $assignedIds[$child->id] = true;
                $membersByCode->put($child->referral_code, $child);
                $nextLevel->push($child);
            }

            $currentLevel = $nextLevel;
        }

        // Downline Team Members for Left & Right Subtrees
        $leftMembers = $this->getSubtreeTeamMembers($root->left_child);
        $rightMembers = $this->getSubtreeTeamMembers($root->right_child);

        $leftUserIds = $leftMembers->pluck('id')->all();
        $rightUserIds = $rightMembers->pluck('id')->all();

        $leftBusiness = ! empty($leftUserIds)
            ? (float) UserPackage::whereIn('user_id', $leftUserIds)->where('status', 'active')->sum('invested_amount')
            : 0.00;

        $rightBusiness = ! empty($rightUserIds)
            ? (float) UserPackage::whereIn('user_id', $rightUserIds)->where('status', 'active')->sum('invested_amount')
            : 0.00;

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
            'left_count' => count($leftUserIds),
            'right_count' => count($rightUserIds),
            'left_active' => $leftActiveCount,
            'left_inactive' => $leftInactiveCount,
            'right_active' => $rightActiveCount,
            'right_inactive' => $rightInactiveCount,
            'total_team' => count($leftUserIds) + count($rightUserIds),
            'total_business' => $leftBusiness + $rightBusiness,
        ];
    }

    /**
     * Get all team members recursively in a leg/branch (by placement and sponsor downline).
     */
    private function getSubtreeTeamMembers(?User $branchRoot): Collection
    {
        if (! $branchRoot) {
            return collect();
        }

        $members = collect([$branchRoot]);
        $currentCodes = collect([$branchRoot->referral_code]);
        $visitedIds = [$branchRoot->id => true];

        for ($depth = 0; $depth < 50 && $currentCodes->isNotEmpty(); $depth++) {
            $children = User::where(function ($q) use ($currentCodes) {
                $q->whereIn('placement_parent_code', $currentCodes)
                    ->orWhereIn('sponsor_code', $currentCodes);
            })
                ->whereNotIn('id', array_keys($visitedIds))
                ->with(['sponsor', 'userPackages', 'transactions'])
                ->withCount('directMembers')
                ->get();

            if ($children->isEmpty()) {
                break;
            }

            foreach ($children as $child) {
                $visitedIds[$child->id] = true;
            }

            $members = $members->merge($children);
            $currentCodes = $children->pluck('referral_code');
        }

        return $members->unique('id')->values();
    }
}
