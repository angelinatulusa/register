<?php if (isset($_GET['code']))  { die(highlight_file(__FILE__, 1));}?>
<?php
if(isset($_POST['submit'])){
    // Создаем или загружаем существующий XML-документ
    $xmlDoc = new DOMDocument("1.0","UTF-8");
    $xmlDoc->preserveWhiteSpace = false;

    if (file_exists('tootajad.xml')) {
        $xmlDoc->load('tootajad.xml');
    }

    // Создаем новый элемент <date>
    $xml_toode = $xmlDoc->createElement("date");

    // Создаем элементы для данных формы и добавляем их в <date>
    $aeg = $xmlDoc->createElement("aeg", $_POST['aeg']);
    $tootaja = $xmlDoc->createElement("tootaja");
    $nimi = $xmlDoc->createElement("nimi", $_POST['nimi']);
    $isikukood = $xmlDoc->createElement("isikukood", $_POST['isikukood']);

    $tootaja->appendChild($nimi);
    $tootaja->appendChild($isikukood);
    $aeg->appendChild($tootaja);
    $xml_toode->appendChild($aeg);

    // Добавляем <date> в корневой элемент
    $root = $xmlDoc->documentElement;
    $root->appendChild($xml_toode);

    // Сохраняем XML-документ
    $xmlDoc->formatOutput = true;
    $xmlDoc->save('tootajad.xml');
}
$dates = simplexml_load_file('tootajad.xml');
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toote lisamine</title>
</head>
<style>
    th, td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
</style>
<body>
<h2>Toote sisestamine</h2>
<form action="" method="post" name="vorm1">
    <table>
        <tr>
            <td><label for="nimi">Kirjuta inimese nimi:</label></td>
            <td><input type="text" name="nimi" id="nimi"></td>
        </tr>
        <tr>
            <td><label for="isikukood">Kirjuta oma kood:</label></td>
            <td><input type="text" name="isikukood" id="isikukood" maxlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '');"></td>
        </tr>
        <tr>
            <td><label for="aeg">Kirjuta aeg:</label></td>
            <td><input type="time" name="aeg" id="aeg"></td>
        </tr>
        <tr>
            <td><input type="submit" name="submit" id="submit" value="Sisesta"></td>
            <td></td>
        </tr>
    </table>
</form>
<h2>Tootajad</h2>
    <table>
        <tr>
            <th>Nimi</th>
            <th>Isikukood</th>
            <th>Aeg</th>
            <th>Delete</th>
        </tr>
        <?php
        foreach ($dates->date as $date) {
            echo "<tr>";
            echo "<td>{$date->aeg->tootaja->nimi}</td>";
            echo "<td>{$date->aeg->tootaja->isikukood}</td>";
            echo "<td>{$date->aeg['aeg']}</td>";
            echo "<td><button onclick='deleteRow(this)'>Delete(nado dodelatj stobi rabotala)</button></td>";
                echo "</tr>";
            }
        ?>
    </table>
</body>
</html>
