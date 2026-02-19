<?php

namespace App\Models;

use JsonSerializable;


class Accounting extends BaseModel implements JsonSerializable
{
    public static function create(string $date, string $name, string $url, int $memberId) : Accounting
    {
        $DB = \App\Database\DB::getInstance();

        $id = $DB->query("INSERT INTO COMPTABILITE (date_comptabilite, nom_comptabilite, url_comptabilite, id_membre)
                    VALUES (?, ?, ?, ?)", "sssi", [$date, $name, $url, $memberId]);

        return new Accounting($id);
    }

    public function delete() : void
    {
        $this->DB->query("DELETE FROM COMPTABILITE WHERE id_comptabilite = ?", "i", [$this->id]);
    }

    public static function getInstance($id): ?Accounting
    {
        $DB = \App\Database\DB::getInstance();
        $result = $DB->select("SELECT * FROM COMPTABILITE WHERE id_comptabilite = ?", "i", [$id]);

        if (count($result) == 0) {
            return null;
        }

        return new Accounting($id);
    }

    public function jsonSerialize(): array
    {
        $data = $this->DB->select("SELECT * FROM COMPTABILITE WHERE id_comptabilite = ?", "i", [$this->id])[0];

        $data['user'] = $this->DB->select("SELECT * FROM MEMBRE WHERE id_membre = ?", "i", [$data['id_membre']])[0];

        unset($data['id_membre']);

        return $data;
    }

    public static function bulkFetch() : array
    {
        $DB = \App\Database\DB::getInstance();
        return $DB->select("SELECT * FROM COMPTABILITE");
    }

    public function __toString() : string
    {
        return json_encode($this);
    }
}