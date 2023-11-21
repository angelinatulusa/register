<?php

$filename = 'tootajad.json';
$ids = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];

$maksId = 0;

foreach ($ids as $id) {
    $praeguId = (int) $id['id'];
    if ($praeguId > $maksId) {
        $maksId = $praeguId;
    }
}

$uusId = $maksId + 1;

if (isset($_POST['submit'])) {
    $uusToode = [
        'id' => $uusId,
        'aeg' => $_POST['aeg'],
        'tootaja' => [
            'nimi' => $_POST['nimi'],
            'isikukood' => $_POST['isikukood'],
        ],
        'valik' => $_POST['valik'],
    ];

    $ids[] = $uusToode;

    file_put_contents($filename, json_encode($ids, JSON_PRETTY_PRINT));

    header("Location: {$_SERVER['REQUEST_URI']}");
    exit;
}

function Kustuta($data, $id) {
    $filteredData = array_filter($data, function ($item) use ($id) {
        return (int)$item['id'] !== (int)$id;
    });

    file_put_contents('tootajad.json', json_encode(array_values($filteredData), JSON_PRETTY_PRINT));
}

if (isset($_POST['delete'])) {
    $idToDelete = $_POST['delete'];
    Kustuta($ids, $idToDelete);
    $ids = json_decode(file_get_contents($filename), true);
}

function OtsiIsikukoodiga($data, $isikukood) {
    $results = [];

    foreach ($data as $item) {
        $employeeIsikukood = $item['tootaja']['isikukood'];
        if ($isikukood == $employeeIsikukood) {
            $result = [
                'nimi' => $item['tootaja']['nimi'],
                'isikukood' => $employeeIsikukood,
                'aeg' => $item['aeg'],
                'valik' => is_array($item['valik']) ? implode(', ', $item['valik']) : $item['valik'],
            ];
            $results[] = $result;
        }
    }

    return $results;
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

<body>
<h1>Firma töötajate register</h1>
<form action="" method="post" name="vorm1">
    <table>
        <tr>
            <td><label for="nimi">Sisesta inimese nimi:</label></td>
            <td><input type="text" name="nimi" id="nimi"></td>
        </tr>
        <tr>
            <td><label for="isikukood">Sisesta oma kood:</label></td>
            <td><input type="text" name="isikukood" id="isikukood" maxlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '');"></td>
        </tr>
        <tr>
            <td><label for="aeg">Sisesta aeg:</label></td>
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
    foreach ($ids as $toode) {
        echo "<tr>";
        echo "<td>{$toode['tootaja']['nimi']}</td>";
        echo "<td>{$toode['tootaja']['isikukood']}</td>";
        echo "<td>{$toode['aeg']}</td>";
        echo "<td>{$toode['valik']}</td>";
        echo "<td><form action='' method='post'><input type='hidden' name='delete' value='{$toode['id']}'><button type='submit'>Kustuta</button></form></td>";
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
$otsiVastused = OtsiIsikukoodiga($ids, $otsitavIsikukood);

?>
<table>
    <tr>
        <th>Nimi</th>
        <th>Isikukood</th>
        <th>Aeg</th>
        <th>Algus/lõpp</th>
    </tr>
    <?php
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
<a id="JSON" href="tootajad.json">JSON fail</a>
</br>
<a id="XML" href="https://tulusa21.thkit.ee/register/kuvaminejson.php">xml</a>
</body>
</html
