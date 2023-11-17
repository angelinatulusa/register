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
    $valik = $xmlDoc->createElement("valik", $_POST['valik']);

    $tootaja->appendChild($nimi);
    $tootaja->appendChild($isikukood);
    $aeg->appendChild($tootaja);
    $aeg->appendChild($valik);
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
    body {
        font-family: Arial, sans-serif;
        background-color: #fff2e6; /* Более светлый фон */
        margin: 0;
        padding: 0;
    }

    h1 {
        color: #cc6600;
        text-align: center;
        padding: 10px;
    }

    table {
        border-collapse: collapse;
        width: 50%;
        margin: 20px auto;
        background-color: #fff;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

    th, td {
        border: 1px solid #dddddd;
        padding: 8px;
    }

    th {
        background-color: #b35900;
        color: #fff;
    }

    td:last-child {
        text-align: center;
    }

    input[type="text"], input[type="time"] {
        width: 100%;
        padding: 5px;
        margin: 5px 0;
    }

    select {
        width: 100%;
        padding: 5px;
        margin: 5px 0;
    }

    input[type="submit"] {
        background-color: #b35900;
        color: #fff;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 10px;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    button {
        background-color: #dc3545;
        color: #fff;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 10px;
    }

    button:hover {
        background-color: #bb2d3b;
    }
</style>

<body>
<h1>Firma töötajate register</h1>
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
            <td><label for="valik">Kas olete just saabunud või juba lahkute?</label></td>
            <td>
                <select name="valik" id="valik">
                    <option value="algus">just saabun</option>
                    <option value="lõpp">juba lahkun</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><input type="submit" name="submit" id="submit" value="Sisesta"></td>
            <td></td>
        </tr>
    </table>
</form>
<h1>Tootajad</h1>
    <table>
        <tr>
            <th>Nimi</th>
            <th>Isikukood</th>
            <th>Aeg</th>
            <th>Algus/lõpp</th>
        </tr>
        <?php
        foreach ($dates->date as $date) {
            echo "<tr>";
            echo "<td>{$date->aeg->tootaja->nimi}</td>";
            echo "<td>{$date->aeg->tootaja->isikukood}</td>";
            echo "<td>{$date->aeg}</td>";
            echo "<td>{$date->aeg->valik}</td>";
            echo "<td><button onclick='deleteRow(this)'>Delete(nado dodelatj stobi rabotala)</button></td>";
                echo "</tr>";
            }
        ?>
    </table>
</body>
</html>
