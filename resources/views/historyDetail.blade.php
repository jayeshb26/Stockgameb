<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href="{{ url('css/history.css') }}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="assetss/img/favicon.ico" />
    <title>Fundeal GAME | {{ $data['game'] }}</title>
</head>
<script>
    function mobRoulette36TimerWindow(gameName, groupId) {
        var left = (screen.width / 2) - (350 / 2);
        var top = (screen.height / 2) - (1100 / 2);
        var targetWin = window.open("game/multiplayer/" + gameName + "/" + groupId + "", "title",
            'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=640,height=700, top=' +
            top + ', left=' + left);
    }
</script>

<body>
    <div class="Agent_Game_Det_wrap">
        <div class="Agent_game_Left" style="width:500px;">
            <div class="Agent_game_tit_wrap" style="width:500px;">
                <div class="Agent_game_name">Game Name</div>
                <div class="Agent_game_val" style="width:auto">: {{ ucfirst($data['game']) }}</div>
            </div>
        </div>
    </div>
    <div class="tableWrap">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
                <tr>
                    <td class="NTblHdrWrap">
                        <table class="NTblHdrTxt" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <td class="NTblHdrTxt">USERNAME</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="NTblHdrWrap">
                        <table class="NTblHdrTxt" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <td class="NTblHdrTxt">HAND ID</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="NTblHdrWrap">
                        <table class="NTblHdrTxt" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <td class="NTblHdrTxt">TIME OF BET</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="NTblHdrWrap">
                        <table class="NTblHdrTxt" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <td class="NTblHdrTxt">TOTAL BET</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="NTblHdrWrap">
                        <table class="NTblHdrTxt" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <td class="NTblHdrTxt">TOTAL WIN</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="NTblHdrWrap">
                        <table class="NTblHdrTxt" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <td class="NTblHdrTxt">WINNING NO</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="NSTb2HdrWrap ">
                        <table class="SHdr1line" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <td class="">{{ $data['userName'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="NSTb2HdrWrap ">
                        <table class="NSTblHdrTxt" width="300" align="center">
                            <tbody>
                                <tr>
                                    <td>{{ $data['_id'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="NSTb2HdrWrap ">
                        <table class="NSTblHdrTxt" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <td>{{ createDateFormat($data['createdAt']) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="NSTb2HdrWrap ">
                        <table class="NSTblHdrTxt" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <td>{{ $data['bet'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="NSTb2HdrWrap ">
                        <table class="NSTblHdrTxt" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <td>{{ $data['won'] }}
                                        <table cellspacing="0" cellpadding="0" border="0" align="center">
                                            <tbody>
                                                <tr></tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="NSTb2HdrWrap ">
                        <table class="NSTblHdrTxt" width="100%" align="center">
                            <tbody>
                                <tr>
                                    <td>{{ $data['winPosition'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="Roulette_Wrap">
            @foreach ($data['position'] as $key => $pos)
                @foreach ($pos as $k => $v)
                    @if ($k != 'amount')
                        @switch($k)
                            @case('StraightUp')
                                <div style="min-width:25px;" class="spot{{ $k }}{{ $v[0] }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('1-12')
                                <div style="min-width:25px;" class="spot{{ $k }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('13-24')
                                <div style="min-width:25px;" class="spot{{ $k }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('25-36')
                                <div style="min-width:25px;" class="spot{{ $k }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Column')
                                <div style="min-width:25px;" class="spot{{ $k }}{{ $v[0] }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Corner')
                                <div style="min-width:25px;"
                                    class="spot{{ $k }}{{ $v[0] }}_{{ $v[1] }}_{{ $v[2] }}_{{ $v[3] }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('High')
                                <div style="min-width:25px;" class="spot{{ $k }}{{ $v[0] }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Red')
                                <div style="min-width:25px;" class="spot{{ $k }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Low')
                                <div style="min-width:25px;" class="spot{{ $k }}{{ $v[0] }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Black')
                                <div style="min-width:25px;" class="spot{{ $k }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Odd')
                                <div style="min-width:25px;" class="spot{{ $k }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('SixLine')
                                <div style="min-width:25px;"
                                    class="spot{{ $k }}{{ $v[0] }}_{{ $v[1] }}_{{ $v[2] }}_{{ $v[3] }}_{{ $v[4] }}_{{ $v[5] }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Even')
                                <div style="min-width:25px;" class="spot{{ $k }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Split')
                                <div style="min-width:25px;"
                                    class="spot{{ $k }}{{ $v[0] }}_{{ $v[1] }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Street')
                                <div style="min-width:25px;"
                                    class="spot{{ $k }}{{ $v[0] }}_{{ $v[1] }}_{{ $v[2] }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Zerospiel')
                                <div style="min-width:25px;" class="spot_{{ $k }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Voisinsdu')
                                <div style="min-width:25px;" class="spot_{{ $k }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Orphelins')
                                <div style="min-width:25px;" class="spot_{{ $k }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Tiers')
                                <div style="min-width:25px;" class="spot_{{ $k }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Tiro')
                                <div style="min-width:25px;"
                                    class="spot{{ $k }}{{ $v[0] }}_{{ $v[1] }}_{{ $v[2] }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @case('Basker')
                                <div style="min-width:25px;"
                                    class="spot{{ $k }}{{ $v[0] }}_{{ $v[1] }}_{{ $v[2] }}_{{ $v[3] }}">
                                    <a class="aroulet" href="javascript:void(0);"
                                        title="NORMAL BET">{{ $pos['amount'] }}</a>
                                </div>
                            @break
                            @default
                                <span class="status">Trash</span>
                        @endswitch
                    @endif
                @endforeach
            @endforeach
            <div class="spotw{{ $data['winPosition'] }}">win</a></div>
        </div>
        <div id="Roulette_Neighbour_Wrap">

        </div>
    </div>
    <div style="text-align:center;margin-top:450px">
        <button class="btn btn-default" onClick="self.close()">CLOSE</button>
    </div>
</body>

</html>

<script>
    $(document).bind('keydown', function(e) {
        if (e.ctrlKey && (e.which == 83)) {
            e.preventDefault();
            return false;
        }
    });
</script>
<script language=JavaScript>
    document.addEventListener('contextmenu', event => event.preventDefault());
</script>
<script>
    $(document).keydown(function(event) {
        if (event.keyCode == 123) { // Prevent F12
            return false;
        } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
            return false;
        } else if (event.ctrlKey && event.shiftKey && event.keyCode == 67) { // Prevent Ctrl+Shift+C
            return false;
        } else if (event.ctrlKey && event.shiftKey && event.keyCode == 74) { // Prevent Ctrl+Shift+J
            return false;
        } else if (event.ctrlKey && event.keyCode == 85) { // Prevent Ctrl+U
            return false;
        }
    });
</script>
