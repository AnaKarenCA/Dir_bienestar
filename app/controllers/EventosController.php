<?php
// app/controllers/EventosController.php
class EventosController extends Controller {
    public function index() {
        // Puedes pasar datos si quieres
        $this->view('eventos/index');
    }
}