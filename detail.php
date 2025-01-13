<?php require_once('vendor/autoload.php');
require_once('Cbrs.php');

$dsn = 'mysql:host=127.0.0.1;dbname=datarumah';
$user = 'root';
$password = '';
$database = new Nette\Database\Connection($dsn, $user, $password);

$id = $_GET['id'];
$rumah = get_rumah_detail($id, $database);

$result = $database->query('SELECT rumah_id, NAMARUMAH,LB,LT,KT,KM HARGA FROM rumah');
$data = [];
foreach($result as $row){
    $data[$row->rumah_id] = pre_process($row->NAMARUMAH.' '.$row->HARGA);
}

$cbrs = new Cbrs();
$cbrs->create_index($data);
$cbrs->idf();
$w = $cbrs->weight();  
$r = $cbrs->similarity($id);
$n = 8;

function pre_process($str){
    $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
    $stemmer = $stemmerFactory->createStemmer();

    $stopWordRemoverFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
    $stopword = $stopWordRemoverFactory->createStopWordRemover();

    $str = strtolower($str);
    $str = $stemmer->stem($str);
    $str = $stopword->remove($str);

    return $str;
}

function get_rumah_detail($id, $db){
    $rs = $db->fetch('SELECT * FROM rumah Where rumah_id = '.$id);
    return $rs;
}

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
            
            <div class="row">
                <div class="col-md-2">
                    <img src="https://via.placeholder.com/150" />
                </div>
                <div class="col-md-10">
                    <h2><span class="label label-primary"><?php echo $rumah->NAMARUMAH?></span></h2>
                    <p><strong>HARGA:</strong> Rp <?php echo number_format ($rumah->HARGA)?></p>
                    <p><strong>LUAS BANGUNAN:</strong> <?php echo $rumah->LB?></p>
                    <p><strong>LUAS TANAH:</strong> <?php echo $rumah->LT?></p>
                    <p><strong>KAMAR TIDUR:</strong> <?php echo $rumah->KT?></p>
                    <p><strong>KAMAR MANDI:</strong> <?php echo $rumah->KM?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3>Rekomendasi RUMAH yang sesuai</h3>
                    <ol>
                        <?php $i=0;?>
                        <?php foreach($r as $k => $row):?>
                            <?php if($i==$n) break;?>
                            <?php if($row==1) continue;?>
                            <?php $h = get_rumah_detail($k, $database);?>
                            <li><a href="detail.php?id=<?php echo $h->rumah_id ?>">
                                <?php echo $h->NAMARUMAH ?></a> (<?php echo $row?>)
                            </li>
                            <?php $i++ ?>
                        <?php endforeach ?>    
                    </ol>
                </div>
            </div>
        </div>
    </body>
</html>
