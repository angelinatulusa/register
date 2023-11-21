<?php if (isset($_GET['code'])) { die(highlight_file(__FILE__, 1)); } ?>
<?php
if (isset($_POST['submit'])) {
    $xmlDoc = new DOMDocument("1.0", "UTF-8");
    $xmlDoc->preserveWhiteSpace = false;

    if (file_exists('tootajad.xml')) {
        $xmlDoc->load('tootajad.xml');
    }

    $xml_toode = $xmlDoc->createElement("date");

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

    $root = $xmlDoc->documentElement;
    $root->appendChild($xml_toode);

    $xmlDoc->formatOutput = true;
    $xmlDoc->save('tootajad.xml');
}
$dates = simplexml_load_file('tootajad.xml');

function Kustuta($xml, $isikukood) {
    $elementsToDelete = [];

    foreach ($xml->date as $date) {
        if ((string)$date->aeg->tootaja->isikukood === $isikukood) {
            $elementsToDelete[] = $date;
        }
    }

    foreach ($elementsToDelete as $element) {
        $node = dom_import_simplexml($element);
        $node->parentNode->removeChild($node);
    }

    $xml->asXML('tootajad.xml');
}

if (isset($_POST['delete'])) {
    $isikukoodToDelete = $_POST['delete'];
    Kustuta($dates, $isikukoodToDelete);
    $dates = simplexml_load_file('tootajad.xml');
}
function OtsiIsikukoodiga($xml, $isikukood) {
    $vastused = array();

    foreach ($xml->date as $date) {
        $InimeneIsikukood = (string) $date->aeg->tootaja->isikukood;
        if ((string) $isikukood == $InimeneIsikukood) {
            $vastus = array(
                'nimi' => (string) $date->aeg->tootaja->nimi,
                'isikukood' => $InimeneIsikukood,
                'aeg' => (string) $date->aeg,
                'valik' => (string) $date->aeg->valik,
            );
            $vastused[] = $vastus;
        }
    }

    return $vastused;
}
?>
<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Töötajate register</title>
</head>

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
        <th>Action</th>
    </tr>
    <?php
    foreach ($dates->date as $date) {
        echo "<tr>";
        echo "<td>{$date->aeg->tootaja->nimi}</td>";
        echo "<td>{$date->aeg->tootaja->isikukood}</td>";
        echo "<td>{$date->aeg}</td>";
        echo "<td>{$date->aeg->valik}</td>";
        echo "<td><form action='' method='post'><input type='hidden' name='delete' value='{$date->aeg->tootaja->isikukood}'><button type='submit'>Delete</button></form></td>";
        echo "</tr>";
    }
    ?>
</table>
<form action="" method="post">
    <h1>Isiku otsing isikukoodi järgi:</h1>
    <input type="text" name="search" id="search" maxlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
    <input type="submit" value="Otsi">
</form>
<?php
if (isset($_POST['search'])) {
    $otsitavIsikukood = $_POST['search'];
    $otsiVastused = OtsiIsikukoodiga($dates, $otsitavIsikukood);

?>
    <table>
        <tr>
            <th>Nimi</th>
            <th>Isikukood</th>
            <th>Aeg</th>
            <th>Algus/lõpp</th>
        </tr>
    <?php
    //Otsingutulemuste kuvamine
    foreach ($otsiVastused as $vastus) {
        echo "<tr>";
        echo "<td>{$vastus['nimi']}</td>";
        echo "<td>{$vastus['isikukood']}</td>";
        echo "<td>{$vastus['aeg']}</td>";
        echo "<td>{$vastus['valik']}</td>";
        echo "</tr>";
    }
}
    ?>
    </table>
<a id="XML" href="tootajad.xml">XML fail</a>
</body>
</html>
