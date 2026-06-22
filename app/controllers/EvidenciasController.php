<?php
// app/controllers/EvidenciasController.php
class EvidenciasController extends Controller {
    public function index() {
        // Puedes pasar datos si quieres
        $this->view('evidencias/index');
    }
}