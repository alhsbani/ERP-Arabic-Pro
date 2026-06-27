<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Create a notification.
     */
    public static function create($userId, $companyId, $type, $title, $message, $data = [], $actionUrl = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'company_id' => $companyId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Notify invoice created.
     */
    public static function invoiceCreated($invoice)
    {
        self::create(
            $invoice->user_id,
            $invoice->company_id,
            'invoice',
            __('notifications.invoice_created'),
            __('notifications.invoice_created_message', ['invoice' => $invoice->invoice_number]),
            ['invoice_id' => $invoice->id],
            route('invoices.show', $invoice)
        );
    }

    /**
     * Notify leave request.
     */
    public static function leaveRequestCreated($leaveRequest)
    {
        self::create(
            $leaveRequest->approved_by,
            $leaveRequest->company_id,
            'leave',
            __('notifications.leave_request'),
            __('notifications.leave_request_message', ['employee' => $leaveRequest->employee->full_name]),
            ['leave_request_id' => $leaveRequest->id],
            route('leave.index')
        );
    }

    /**
     * Notify payment received.
     */
    public static function paymentReceived($payment)
    {
        self::create(
            $payment->user_id,
            $payment->company_id,
            'payment',
            __('notifications.payment_received'),
            __('notifications.payment_received_message', ['amount' => $payment->amount]),
            ['payment_id' => $payment->id],
            route('invoices.show', $payment->invoice_id)
        );
    }
}
