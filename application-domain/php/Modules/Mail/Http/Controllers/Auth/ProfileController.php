<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('sendportal::profile.show');
    }

    public function edit(): View
    {
        return view('sendportal::profile.edit');
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return redirect()->back()->with('success', __('Your profile was updated successfully!'));
    }
}
