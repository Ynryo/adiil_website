<?php

require_once 'src/model/bdd/grade.php';
require_once 'src/model/other/files_save.php';

class grade {
    public function show() {
        $products = getAllGrades();

        include 'src/view/base/gradeView.php';
    }
}
