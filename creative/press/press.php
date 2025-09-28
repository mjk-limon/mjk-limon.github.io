<?php

[

    'd' => $date,
    't' => $title,
    'b' => $body,
    'p' => $password,

] = $_POST;

if ($password !== 'LIMON') {

    exit(header('Location: index.html'));
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('./solaimanlipi.ttf');
        }

        body {
            margin: 0;
            padding: 0;
        }

        .text-body {
            font-size: 24px;
            line-height: 1.5em;
            font-family: 'SolaimanLipi';
        }

        #print-btn {
            background: #009d0a;
            padding: 0.5em 3em;
            display: block;
            margin: 20px auto;
            color: #fff;
            font-weight: bold;
            font-size: 20px;
            text-transform: uppercase;
        }

        @media print {
            @page {
                size: 1275px 1650px;
                margin: 0;
                padding: 0;
            }

            html,
            body {
                width: 1275px;
                height: 1650px;
            }

            #print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div style="position: relative; width: 1275px;">
        <img src="body.jpg" style="width: 100%;" />

        <div style="position: absolute;top: 320px; left: 75px;right: 75px;">
            <div style="text-align: right; font-size: 20px; font-weight: bold; font-family: 'SolaimanLipi';">
                তারিখঃ <?php echo $date ?>
            </div>

            <div class="text-body" style="margin-top: 100px; margin-left: 45px; margin-right: 45px;">

                <div style="text-align: center;margin-bottom: 30px;">
                    <?php echo $title ?>
                </div>

                <div style="text-align: justify;">
                    <?php echo $body ?>
                </div>

                <div style="margin-top: 50px; font-weight: bold;">
                    বার্তা প্রেরক<br />
                    <img src="sign.png" alt=""><br />
                    সাখাওয়াতুল ইসলাম খান পরাগ<br />
                    দপ্তর সম্পাদক (যুগ্ম-সাধারণ সম্পাদক পদমর্যাদা)<br />
                    জগন্নাথ বিশ্ববিদ্যালয় ছাত্রদল<br />
                </div>
            </div>
        </div>

    </div>

    <button onclick="window.print()" id="print-btn">Print</button>
</body>

</html>