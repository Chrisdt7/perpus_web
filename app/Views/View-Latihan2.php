<html>
    <head>
        <title>Latihan 2</title>
        <style>
            body {
                background-color: gray;
                color: white;
            }
            .welcome {
                width: 350px;
                text-align: center;
                font-size: 20;
            }
            .test {
                width: 350px;
                text-align: center;
            }
            .nilai1 {
                color: red;
            }
            .nilai2 {
                color: blue;
            }
        </style>
    </head>
    <body>
        <p>
        <div class='test'>
            Halo Kawan.. Yuk kita belajar web programming..!!!<br>
            <div class='nilai1'>Nilai 1 = <?= $nilai1; ?></div>
            <div class='nilai2'>Nilai 2 = <?= $nilai2; ?></div>
            hasil dari pemodelan dengan metode penjumlahan yaitu 
            <?= $nilai1 . " + " . $nilai2 . " = " . $hasil; ?>
        </div>
    </body>
</html>