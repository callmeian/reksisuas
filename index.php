<?php require_once('vendor/autoload.php');
require_once('Cbrs.php');

$dsn = 'mysql:host=127.0.0.1;dbname=datarumah';
$user = 'root';
$password = '';
$database = new Nette\Database\Connection($dsn, $user, $password);

$result = $database->query('SELECT rumah_id, NAMARUMAH, HARGA FROM rumah order by rand() limit 0,10');

?>
<!doctype html>
<html lang="en">
    <head>
        <title>Content-based Filtering</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    </head>

    <body>
        <div class="container theme-showcase">
            <div class="jumbotron">
                <h1>Daftar RUMAH di JAKARTA</h1>
                <p>Contoh implementasi Sistem rekomendasi berbasis kontent menggunakan metode TF-IDF dan Cosine Similarity</p>
            </div>
            <div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NAMA RUMAH</th>
                            <th>HARGA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1?>
                        <?php foreach($result as $row):?>
                        <tr>
                            <td><?php echo $no++?></td>
                            <td><a href="detail.php?id=<?php echo $row->rumah_id ?>">
                                <?php echo $row->NAMARUMAH ?></a>
                            </td>
                            <td><?php echo 'Rp '.number_format($row->HARGA) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
