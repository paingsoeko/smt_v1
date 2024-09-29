<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $fileName ?? date()->format('d') }}</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 12px;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            color: #333;
        }

        ol {
            counter-reset: item;
            list-style-type: none;
            padding: 0;
        }

        li {
            counter-increment: item;
            background-color: #ffffff;
            padding: 8px;

            position: relative;
        }

        li::before {
            content: counter(item) ".";
            font-weight: bold;
            position: absolute;
            left: -40px;
            top: 20px;
            font-size: 20px;
            color: #333;
        }

        .item-title {
            font-size: 12px;
            font-weight: bold;
            color: #444;
        }

        .item-content {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>

    <h1>{{ $myVariable }}</h1>

      <ol>
        @foreach ($rows as $row)
            <li>
{{--                <div class="item-title"><span>Student ID: </span>{{ $row['student_code'] }}</div>--}}
                <div class="item-content">
                    @foreach ($columns as $column)
                        <p><strong>{{ $column->getLabel() }}:</strong> {{ $row[$column->getName()] }}</p>
                    @endforeach
                </div>
            </li>
        @endforeach
    </ol>

</body>
</html>
