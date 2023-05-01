<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>

    <style>
        * {
            font-family: Arial, Helvetica, sans-serif
        }
        table,
        tr,
        td,
        ul,
        li {
            margin: 0;
            padding: 0;
        }

        ul {
            list-style-type: none;
            padding: 5px;
        }

        table {
            min-width: 300px
        }

        table tr td {
            min-width: 50px;
        }

        .info-user {
            padding: 12px;
            border: 1px solid #ccc;
            width: 100%;
        }
    </style>
</head>
<body>

   <table class='info-user'>
     <tr>
        <td>
            <table>
                <tr>
                    <td>Empresa X solução em TI</td>
                </tr>
                <tr>
                    <td>Rua X centro da cidade Y - PR</td>
                </tr>
                <tr>
                    <td>(00) 0000-0000 | email@empresax.com.br</td>
                </tr>
            </table>
        </td>
     </tr>

     <tr>
        <td colspan=5>
            <hr/>
        </td>
     </tr>

     <tr>
        <td>
            <table>
                <tr>
                    <td>
                        Nome: {{$name}}
                    </td>
                </tr>

                <tr>
                    <td> 
                        E-mail: {{$email}}
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Função: Analista de Informatica pleno nivel 1
                    </td>
                </tr>
            </table>
        </td>

        <td>
             <table>
                <tr>
                    <td>
                        Entrada: {{$hour_entry}}
                    </td>
                </tr>

                <tr>
                    <td> 
                        Pausa: {{$hour_pause}}
                    </td>
                </tr>
                
                <tr>
                    <td>
                        Retorno: {{$hour_return}}
                    </td>
                </tr>

                <tr>
                    <td>
                        Saida: {{$hour_exit}}
                    </td>
                </tr>
            </table>
        </td>
     </tr>
   </table>

   <table>
       <tr>
        <td>Data</td>
        <td>Registros</td>
       </tr>
        @foreach($content as $item)
            <tr>
                <td>{{date('d/m/Y', strtotime($item['date']))}}</td>
                {{$aux = 0}}
                {{$count = 1}}
                @foreach($item['hours'] as $hour)
                    <td>
                        <ul>
                            <li>
                                {{$aux === 0 ? "Ent.${count}" : "Sai.${count}"}}
                            </li>
                            <li>
                                {{$hour}}
                            </li>
                        </ul>
                    </td>

                    {{$aux === 0 ? $aux = 1 : $aux = 0 }}
                    {{$aux === 0 ? $count = $count + 1 : ''}}
                @endforeach 
            </tr>
        @endforeach
    </table>
</body>
</html>