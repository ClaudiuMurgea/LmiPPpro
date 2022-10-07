<div id="cashouts_page" class="full_width">
    <style>
        .bghandler{
            background: url("{{ asset('images/background-images/cashouts.jpg') }}");
            min-height:100vh;
            max-height:100vh;
            min-width:100vw;
            max-width:100vw;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-size: cover;
        }
    </style>
    <table class="table custom_table_background max_width text-white bolder radius_10 cashout_page mt_2 no_border_b">
        <thead>
            <tr>                                                   
                <th class="cashouts_slot-wrapper text-left"  scope="col">           {{ Translate('Slot') }}         </th>
                <th class="cashouts_slot-wrapper pl_0-8"     scope="col">           {{ Translate('Type') }}         </th>
                <th class="cashouts_value-wrapper text-left" scope="col">           {{ Translate('Value') }}        </th>
                <th style="padding-left:10px;"               scope="col">           {{ Translate('Timestamp') }}    </th>
            </tr>
        </thead>
        <tbody>
            @if(!count($cashoutValues))
                <td class="no_border_b text-left text-center" colspan='3'> {{ Translate('This slot machine does not have cashouts!') }}    </td>
            @else
                @foreach($cashoutValues as $index => $cashout)
                    <tr>
                        <td class="@if($loop->last) no_border_b @endif text-left pl_2"> {{ $cashout->Slot }}            </td>
                        <td class="@if($loop->last) no_border_b @endif text-left">      {{ $cashout->CashoutType }}     </td>
                        <td class="@if($loop->last) no_border_b @endif text-left" >     {{ $cashout->Amount }}          </td>
                        <td class="@if($loop->last) no_border_b @endif">                {{ $cashout->Timestamp }}       </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

