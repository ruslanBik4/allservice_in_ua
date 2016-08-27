<?php
class investorsView
{
    // Данные, полученные из модели для вставки в шаблон
    protected $data;
    // Путь к представлению, полученный из контроллера
    protected $path;
    // Путь к главному шаблону
    protected $layout = '../views/investors/investorsLayout.html';

    /**
     * Принимает данные  $data и путь к представлению $path  из контроллера
     * investorsView constructor.
     * @param null $data
     * @param $path
     */
    public function __construct($data = null, $path)
    {
        $this->data = $data;
        $this->path = $path;
    }

    /**
     * Объединяет главный шаблон $layout с необходимым представлением, возвращает готовую страницу $page
     * @return string
     */
    public function render(){
        $data = $this->data;

        ob_start();

        include($this->path);
        $content = ob_get_clean();

        include($this->layout);
        $page = ob_get_clean();

        ob_end_clean();

        return $page;
    }

}