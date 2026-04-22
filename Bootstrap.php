<?php
/**
 * Bootstrap.php
 */

namespace Plugin\GuestOrderQuery;

class Bootstrap
{
    public function boot()
    {
        $this->addNavMenu();
    }

    /**
     * 在前台导航菜单添加「订单查询」入口
     */
    private function addNavMenu()
    {
        add_hook_filter('menu.content', function ($data) {
            $data[] = [
                'name' => trans('GuestOrderQuery::query.nav_title'),
                'link' => shop_route('guest_order_query'),
            ];

            return $data;
        }, 0);
    }
}