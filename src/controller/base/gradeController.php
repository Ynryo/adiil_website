<?php

require_once 'src/model/bdd/grade.php';
require_once 'src/model/utils/files_save.php';

class Grade
{
    public function show()
    {
        $products = getAllGrades();
        $gradeMembre = null;
        if (!empty($_SESSION['userid'])) {
            $gradeMembre = getGradeMembre($_SESSION['userid']);
        }

        include_once 'src/view/base/gradeView.php';
    }
}
