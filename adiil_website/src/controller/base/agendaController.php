<?php

require_once 'src/model/bdd/membre.php';
require_once 'src/model/utils/files_save.php';

class agenda {
    public function show() {
        include 'src/view/base/agendaView.php';
    }
}