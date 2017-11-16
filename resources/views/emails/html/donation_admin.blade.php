<table class="action" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td>
        <td align="center">
            {{ $details['first_name'] }} {{ $details['last_name'] }}<br>
            {{ $details['address'] }}<br>
            {{ $details['phone'] }}<br>
            {{ $details['email'] }}<br>
        </td>
    </tr>
</table>
<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th>Amount</th>
                    @if ( $details['is_recurring'] != 1 )
                    <th>Transaction ID</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td align="center">
                        ${{ $details['amount'] }}
                    </td>
                    @if ( $details['is_recurring'] != 1 )
                    <td align="center">
                        {{ $details['transaction_id'] }}
                    </td>
                    @endif
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
@if ( $details['is_recurring'] == 1 )
<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                        <th>Recurring</th>
                        <th>Start</th>
                        <th>Profile ID</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                        <td align="center">
                            {{ $details['recurring_frequency'] }}
                        </td>
                        <td align="center">
                            {{ $details['recurring_start'] }}
                        </td>
                        <td align="center">
                            {{ $details['plan_id'] }}
                        </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
@endif
<p>Comments</p>
<p><i>{{ $details['comment'] }}</i></p>