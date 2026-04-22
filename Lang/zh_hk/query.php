<?php
return [
    'nav_title'          => '订单查询',
    'page_title'         => '游客订单查询',
    'page_desc'          => '无需登录，通过多种方式快速查询您的订单状态',

    // 查询类型
    'type_number'        => '订单号',
    'type_email'         => '邮箱',
    'type_phone'         => '手机号',
    'type_name'          => '收货人',
    'type_address'       => '收货地址',

    // 占位符
    'placeholder_number'  => '请输入完整订单号，如：20240101123456',
    'placeholder_email'   => '请输入下单时填写的邮箱地址',
    'placeholder_phone'   => '请输入下单时填写的手机号码',
    'placeholder_name'    => '请输入收货人姓名',
    'placeholder_address' => '请输入收货地址关键词',

    // 提示
    'hint_number'        => '请输入完整的订单号进行精确查询',
    'hint_email'         => '将返回该邮箱下的所有订单（最多20条）',
    'hint_phone'         => '将返回该手机号下的所有订单（最多20条）',
    'hint_name'          => '将返回收货人姓名匹配的订单',
    'hint_address'       => '支持城市、街道等关键词模糊查询',

    // 按钮
    'btn_search'         => '立即查询',
    'searching'          => '正在查询中，请稍候...',

    // 结果
    'no_result'          => '未找到匹配的订单，请确认信息是否正确',
    'query_error'        => '查询失败，请稍后重试',

    // 订单字段
    'order_number'       => '订单号',
    'label_name'         => '收货人',
    'label_email'        => '邮箱',
    'label_phone'        => '手机号',
    'label_address'      => '收货地址',
    'label_total'        => '订单金额',
    'shipping_method'    => '配送方式',
    'payment_method'     => '支付方式',
    'products'           => '商品明细',

    // 订单状态
    'status_unpaid'      => '待付款',
    'status_paid'        => '已付款',
    'status_processing'  => '处理中',
    'status_shipped'     => '已发货',
    'status_complete'    => '已完成',
    'status_refund'      => '已退款',
    'status_cancel'      => '已取消',
];
