<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Workspaces;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Models\Invitation;
use Modules\Mail\Services\Workspaces\AcceptInvitation;

class PendingInvitationController extends Controller
{
    /** @var AcceptInvitation */
    protected $acceptInvitation;

    public function __construct(AcceptInvitation $acceptInvitation)
    {
        $this->acceptInvitation = $acceptInvitation;
    }

    /**
     * @throws Exception
     */
    public function accept(Request $request, Invitation $invitation): RedirectResponse
    {
        abort_unless($request->user()->id === $invitation->user_id, 404);

        if ($invitation->isExpired()) {
            return redirect()->back()->with('error', __('The invitation is no longer valid.'));
        }

        $this->acceptInvitation->handle($request->user(), $invitation);

        return redirect()->route('backend.workspaces.index');
    }

    /**
     * @throws Exception
     */
    public function reject(Request $request, Invitation $invitation): RedirectResponse
    {
        abort_unless($request->user()->id === $invitation->user_id, 404);

        $invitation->delete();

        return redirect()->route('backend.workspaces.index');
    }
}
