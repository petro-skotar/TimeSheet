<table>
    @if(!empty($WorkerClientHours) && count($WorkerClientHours) > 0)

        @foreach($WorkerClientHours->unique('client_id') as $wc)
            @if($wc->client()->active)
                <thead>
                    <tr>
                        <td style="font-size: 18px; font-weight: bold; font-family: DejaVu Sans;" colspan="{{ (in_array($selectCountDays, [28,29,30,31]) ? 4 : 3) }}">{{ $wc->client()->name }}</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $processed = [];
                        $all_clients_hours = 0;
                    @endphp
                    @foreach($WorkerClientHours->where('client_id',$wc->client_id) as $wc_workers)
                        @if(!in_array($wc_workers->worker_id,$processed))
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td width="50" style="font-family: DejaVu Sans; word-wrap: break-word; @if($wc_workers->worker()->active == 0) text-decoration: line-through; @endif">{{ $wc_workers->worker()->name }} ({{ $wc_workers->worker()->position }}, @if(in_array($selectCountDays, [28,29,30,31])){{ $wc_workers->worker()->get_current_salary(\Date::parse($wc_workers->created_at)->format('Y'), \Date::parse($wc_workers->created_at)->format('n')) }}@endif ₽ / мес)</td>
                            <td style="text-align: right; font-family: DejaVu Sans;">
                                @php
                                    $clients_hours = $WorkerClientHours->where('client_id',$wc->client_id)->where('worker_id',$wc_workers->worker_id)->sum('hours');
                                    $all_clients_hours += $clients_hours*$wc_workers->worker()->get_pay_per_one_hour(\Date::parse($wc_workers->created_at)->format('Y'), \Date::parse($wc_workers->created_at)->format('n'));
                                @endphp
                                {{ $clients_hours }}&nbsp;ч.
                            </td>
                            @if(in_array($selectCountDays, [28,29,30,31]))
                            <td style="text-align: right; font-family: DejaVu Sans;">{{ $WorkerClientHours->where('client_id',$wc->client_id)->where('worker_id',$wc_workers->worker_id)->sum('hours')*$wc_workers->worker()->get_pay_per_one_hour(\Date::parse($wc_workers->created_at)->format('Y'), \Date::parse($wc_workers->created_at)->format('n')) }} {{ $currency }}</td>
                            @endif
                        </tr>
                        @php
                            $processed[] = $wc_workers->worker_id;
                        @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    @if(in_array($selectCountDays, [28,29,30,31]))
                        @php
                            $OPEX = (!empty($wc->client()->fee(\Date::parse($date_or_period[0])->format('Y') ,\Date::parse($date_or_period[0])->format('m')*1)) ? $wc->client()->fee(\Date::parse($date_or_period[0])->format('Y') ,\Date::parse($date_or_period[0])->format('m')*1)->fee*0.35 : 0);
                            $fee = (!empty($wc->client()->fee(\Date::parse($date_or_period[0])->format('Y') ,\Date::parse($date_or_period[0])->format('m')*1)) ? $wc->client()->fee(\Date::parse($date_or_period[0])->format('Y') ,\Date::parse($date_or_period[0])->format('m')*1)->fee : 0);
                            $profit = $fee - $OPEX - $all_clients_hours;
                            $marginality = (!empty($wc->client()->fee(\Date::parse($date_or_period[0])->format('Y') ,\Date::parse($date_or_period[0])->format('m')*1)) ? round($profit*100/$fee,2) : 0);
                        @endphp
                                <tr>
                                    <td></td>
                                    <td colspan="2" style="font-family: DejaVu Sans;">ИТОГО Себестоимость</td>
                                    <td width="13" style="text-align: right; font-family: DejaVu Sans;">{{ $all_clients_hours }} {{ $currency }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2" style="font-family: DejaVu Sans;">OPEX (35%)</td>
                                    <td style="text-align: right; font-family: DejaVu Sans;">{{ round($OPEX,0) }} {{ $currency }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2" style="font-family: DejaVu Sans;">ГОНОРАР</td>
                                    <td style="text-align: right; font-family: DejaVu Sans;">{{ $fee }} {{ $currency }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2" style="font-family: DejaVu Sans;">ПРИБЫЛЬ</td>
                                    <td style="text-align: right; font-weight: bold; font-family: DejaVu Sans;">{{ round($profit,0) }} {{ $currency }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2" style="font-family: DejaVu Sans;">МАРЖИНАЛЬНОСТЬ</td>
                                    <td style="text-align: right; font-weight: bold; font-family: DejaVu Sans;">{{ $marginality }}%</td>
                                </tr>
                    @else
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="text-align: right; font-weight: bold; font-family: DejaVu Sans;">{{ $WorkerClientHours->where('client_id',$wc->client_id)->sum('hours') }}&nbsp;ч.</td>
                    </tr>
                    @endif
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        @if(in_array($selectCountDays, [28,29,30,31]))
                        <td></td>
                        @endif
                    </tr>
                </tfoot>
            @endif
        @endforeach
    @endif
</table>
