<?PHP

use mdm\admin\components\MenuHelper;
use dmstr\widgets\Menu;

$callback = function ($menu) {
    $data = json_decode($menu['data'], true);
    $items = $menu['children'];
    $return = [
        'label' => $menu['name'],
        'url' => [$menu['route']],
    ];
    if ($data) {
        //icon
        isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon'];
        //other attribute e.g. class...
        $return['options'] = $data;
    }
    $items && $return['items'] = $items;
    return $return;
};
$items = MenuHelper::getAssignedMenu(Yii::$app->user->id, NULL, $callback, 1);
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <a href="/" class="sidebar__header">
            <i class="iron iron-B"></i>
            <img src="/img/logo-b.png" alt="">
        </a>
        <?PHP
        echo \biv\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => $items
            ]);
        ?>
    </section>

</aside>
