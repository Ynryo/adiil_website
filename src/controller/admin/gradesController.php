<?php

require_once 'src/model/bdd/grade.php';
require_once 'src/model/utils/files_save.php';

class GradesController
{
    private const REDIRECT_URL = 'Location: /?page=admin-admin/grades';

    public function show()
    {
        $grades = getAllGrades();

        $selectedGrade = null;

        if (isset($_GET['id'])) {
            $selectedGrade = getGrade((int)$_GET['id']);
        } elseif (!empty($grades)) {
            $selectedGrade = getGrade($grades[0]['id_grade']);
        }

        if ($selectedGrade) {
            $selectedGrade = $selectedGrade[0] ?? null;
        }

        include_once 'src/view/admin/panels/grades.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int)$_POST['id'];
        $nom = trim($_POST['nom']) ?: 'N/A';
        $description = trim($_POST['description']);
        $prix = (float)($_POST['prix'] ?? 0);
        $reduction = (int)($_POST['reduction'] ?? 0);

        updateGrade($id, $nom, $description, $prix, $reduction);

        header(self::REDIRECT_URL . '&id=' . $id);
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int)$_POST['id'];
        deleteGrade($id);

        header(self::REDIRECT_URL);
        exit();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $newId = createGrade();

        header(self::REDIRECT_URL . '&id=' . $newId);
        exit();
    }

    public function uploadImage()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int)$_POST['id'];
        $gradeArray = getGrade($id);
        $grade = $gradeArray[0] ?? null;

        if (!$grade) {
            header(self::REDIRECT_URL);
            exit();
        }

        $imageName = saveImage(); // from files_save.php (reads $_FILES['file']['tmp_name'])

        if ($imageName) {
            if (!empty($grade['image_grade']) && $grade['image_grade'] !== 'N/A') {
                deleteFile($grade['image_grade']);
            }
            updateGradeImage($id, $imageName);
        }

        header(self::REDIRECT_URL . '&id=' . $id);
        exit();
    }
}
