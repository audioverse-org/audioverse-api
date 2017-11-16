<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>Amount</th>
                        @if ( $details['is_recurring'] == 1 )
                            <th>Recurring</th>
                            <th>Start</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td align="center">
                        ${{ $details['amount'] }}
                    </td>
                    @if ( $details['is_recurring'] == 1 )
                        <td align="center">
                            {{ $details['recurring_frequency'] }}
                        </td>
                        <td align="center">
                            {{ $details['recurring_start'] }}
                        </td>
                    @endif
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
    <p><b>Note:</b>
        <ul>
        @if ( $details['is_recurring'] == 1 )
            <li>{{ $details['recurring_note_1'] }}</li>
        @endif
            <li>{{ $details['recurring_note_2'] }}</li>
        </ul>
    </p>
