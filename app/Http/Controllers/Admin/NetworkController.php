<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class NetworkController extends Controller
{
    /**
     * Display listing of direct members across network in Admin Panel.
     */
    public function directMembers(Request $request): View
    {
        $query = User::with(['sponsor'])->whereNotNull('sponsor_code');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%")
                    ->orWhere('sponsor_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $directs = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => User::whereNotNull('sponsor_code')->count(),
            'active' => User::whereNotNull('sponsor_code')->where('status', 'active')->count(),
            'left' => User::whereNotNull('sponsor_code')->where('position', 'left')->count(),
            'right' => User::whereNotNull('sponsor_code')->where('position', 'right')->count(),
        ];

        return view('admin.network.direct', compact('directs', 'stats'));
    }

    /**
     * Display Visual Binary Team Tree in Admin Panel.
     */
    public function treeView(Request $request): View|JsonResponse
    {
        $search = $request->query('code') ?? $request->query('id') ?? $request->query('search');

        if ($search) {
            $rootUser = User::where('referral_code', $search)
                ->orWhere('id', $search)
                ->orWhere('name', 'like', "%{$search}%")
                ->first();
        }

        if (! isset($rootUser) || ! $rootUser) {
            $rootUser = User::where('role_id', 2)->whereNull('sponsor_code')->first() ?? User::where('role_id', 2)->first();
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

        return view('admin.network.tree', compact('rootUser', 'treeData', 'directMembers'));
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
