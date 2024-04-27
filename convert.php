<?php


class Util
{
    public static function keyName($key)
    {
        return urlencode(str_replace([':', ' '], ['', '_'], $key));
    }

    public static function import($filename)
    {
        $buffer = file_get_contents($filename);
        return json_decode($buffer, true);
    }

    public static function getSymInfo(array $data)
    {
        $info = ['symbol' => [], 'pricescale' => [], 'description' => []];
        foreach (array_keys($data) as $key) {
            $info['symbol'][] = static::keyName($key);
            $info['pricescale'][] = 10000000000000000000000;
            $info['description'][] = static::keyName($key);
        }
        return $info;
    }

    public static function toRepoFiles(array $data)
    {
        file_put_contents('symbol_info/seed_deboorn_btcsupervaluemap.json', json_encode(static::getSymInfo($data)));
        foreach ($data as $key => $value) {
            $keyName = static::keyName($key);

            $f = fopen("data/{$keyName}.csv", 'r+');
            foreach ($value as $row) {
                fputcsv($f, [
                    date('Ymd\T', strtotime($row['t'])),
                    $row['v'],
                    $row['v'],
                    $row['v'],
                    $row['v'],
                    0
                ]);
            }
            fclose($f);
        }
    }

    public static function gen($filename)
    {
        $data = static::import($filename);
        static::toRepoFiles($data);
    }

}

Util::gen('input.json');

die("\n\nDone!\n\n");
