@extends('layout.master')

@section('content')
<div class="refined-query-page">
    <div class="content-container">
        <header class="header-v3">
            <h1>订单详情查询</h1>
            <div class="sub-line">QUICK ORDER LOOKUP</div>
        </header>

        <div class="search-v3">
            <div class="tab-v3">
                <div class="tab-btn active" onclick="setTab(this, 'name')">按姓名</div>
                <div class="tab-btn" onclick="setTab(this, 'phone')">按手机号</div>
                <div class="tab-btn" onclick="setTab(this, 'number')">按单号</div>
                <div class="tab-btn" onclick="setTab(this, 'email')">按邮箱</div>
            </div>
            <div class="input-v3">
                <input type="text" id="q-input" placeholder="请输入收货人姓名" spellcheck="false">
                <button onclick="doQuery()">查询</button>
            </div>
        </div>

        <div id="result-v3"></div>
    </div>
</div>

<style>
    /* 全局背景：极简淡灰蓝 */
    .refined-query-page {
        min-height: 100vh;
        background: #fcfdfe;
        padding: 50px 15px;
        font-family: "PingFang SC", "Hiragino Sans GB", sans-serif;
    }
    .content-container { max-width: 480px; margin: 0 auto; }

    .header-v3 { text-align: center; margin-bottom: 40px; }
    .header-v3 h1 { font-size: 24px; font-weight: 500; color: #1a1a1a; margin: 0; }
    .sub-line { font-size: 10px; color: #b0b0b0; letter-spacing: 3px; margin-top: 8px; }

    /* 搜索框样式优化 */
    .search-v3 { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); }
    .tab-v3 { display: flex; background: #f7f8f9; border-radius: 12px; padding: 4px; margin-bottom: 20px; }
    .tab-btn { flex: 1; text-align: center; padding: 10px 0; font-size: 13px; color: #888; cursor: pointer; transition: 0.3s; border-radius: 10px; }
    .tab-btn.active { background: #fff; color: #000; font-weight: 600; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }

    .input-v3 { display: flex; gap: 12px; }
    #q-input { flex: 1; border: 1px solid #eee; border-radius: 12px; padding: 12px 15px; outline: none; font-size: 14px; background: #fbfbfb; }
    #q-input:focus { background: #fff; border-color: #000; }
    .input-v3 button { background: #1a1a1a; color: #fff; border: none; border-radius: 12px; padding: 0 25px; cursor: pointer; font-size: 14px; }

    /* --- 核心优化：订单卡片 UI --- */
    .order-card-v3 {
        margin-top: 30px;
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.06);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.02);
        animation: cardFadeIn 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }

    /* 卡片顶部：左侧色条装饰 */
    .card-top-v3 {
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f8f9fa;
        position: relative;
    }
    .card-top-v3::before {
        content: ""; position: absolute; left: 0; top: 25%; height: 50%; width: 4px; background: #000; border-radius: 0 4px 4px 0;
    }
    .order-id-label { font-size: 13px; color: #999; }
    .order-id-val { font-size: 15px; font-weight: 700; color: #1a1a1a; margin-left: 5px; }
    .status-tag-v3 { font-size: 12px; font-weight: 600; color: #000; background: #f0f0f0; padding: 4px 12px; border-radius: 20px; }

    /* 信息详情区：两栏布局更紧凑 */
    .card-info-v3 { padding: 25px; display: grid; gap: 15px; }
    .info-row-v3 { display: flex; justify-content: space-between; align-items: baseline; }
    .info-row-v3 .label { color: #a0a0a0; font-size: 13px; font-weight: 300; }
    .info-row-v3 .value { color: #333; font-size: 14px; font-weight: 500; text-align: right; }
    .price-style { font-size: 18px !important; font-weight: 800 !important; color: #000 !important; }

    /* 动态追踪：圆润的时间轴 */
    .track-v3 { background: #fafafa; border-top: 1px solid #f1f1f1; padding: 25px; }
    .track-title { font-size: 13px; font-weight: 700; color: #1a1a1a; margin-bottom: 15px; display: block; }
    .track-item-v3 { position: relative; padding-left: 20px; border-left: 1px solid #e0e0e0; margin-bottom: 20px; }
    .track-item-v3:last-child { border-left: 1px solid transparent; margin-bottom: 0; }
    .track-dot-v3 { position: absolute; left: -4px; top: 4px; width: 7px; height: 7px; background: #d0d0d0; border-radius: 50%; }
    .track-item-v3.newest .track-dot-v3 { background: #000; box-shadow: 0 0 0 4px rgba(0,0,0,0.05); }
    .track-time-v3 { font-size: 11px; color: #bbb; margin-bottom: 4px; }
    .track-msg-v3 { font-size: 13px; color: #555; line-height: 1.5; }

    @keyframes cardFadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
        .search-v3 { padding: 15px; }
        .input-v3 { flex-direction: column; }
        .input-v3 button { padding: 12px; }
    }
</style>

<script>
    let qType = 'name';

    function setTab(el, type) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        qType = type;
        document.getElementById('q-input').placeholder = '请输入' + el.innerText.replace('按', '');
    }

    async function doQuery() {
        const kw = document.getElementById('q-input').value.trim();
        const container = document.getElementById('result-v3');
        if(!kw) return;

        container.innerHTML = '<div style="text-align:center; padding:60px; color:#ccc; font-size:13px; letter-spacing:2px;">DATA LOADING...</div>';

        try {
            const response = await fetch('<?php echo shop_route("guest_order_query.search"); ?>', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json' 
                },
                body: JSON.stringify({ query_type: qType, keyword: kw })
            });
            const res = await response.json();

            if(res.success && res.data.length > 0) {
                let html = '';
                res.data.forEach(order => {
                    let hLines = '';
                    if(order.histories && order.histories.length > 0) {
                        hLines = `<div class="track-v3"><span class="track-title">状态追踪</span>`;
                        order.histories.forEach((h, idx) => {
                            hLines += `
                                <div class="track-item-v3 ${idx === 0 ? 'newest' : ''}">
                                    <div class="track-dot-v3"></div>
                                    <div class="track-time-v3">${h.time}</div>
                                    <div class="track-msg-v3"><strong>${h.status}</strong> — ${h.comment}</div>
                                </div>`;
                        });
                        hLines += `</div>`;
                    }

                    // 修改点：直接输出 order.total，避免￥￥重复
                    html += `
                        <div class="order-card-v3">
                            <div class="card-top-v3">
                                <div><span class="order-id-label">单号</span><span class="order-id-val">${order.number}</span></div>
                                <span class="status-tag-v3">${order.status_label}</span>
                            </div>
                            <div class="card-info-v3">
                                <div class="info-row-v3"><span class="label">收货人</span><span class="value">${order.name}</span></div>
                                <div class="info-row-v3"><span class="label">联系电话</span><span class="value">${order.phone}</span></div>
                                <div class="info-row-v3"><span class="label">收货地址</span><span class="value">${order.address}</span></div>
                                <div class="info-row-v3"><span class="label">下单时间</span><span class="value">${order.created_at}</span></div>
                                <div class="info-row-v3" style="margin-top:5px;"><span class="label">订单总计</span><span class="value price-style">${order.total}</span></div>
                            </div>
                            ${hLines}
                        </div>`;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = `<div style="text-align:center; padding:60px; color:#ff4d4f; font-size:14px;">${res.message || '未查询到相关记录'}</div>`;
            }
        } catch(e) {
            container.innerHTML = '<div style="text-align:center; padding:60px; color:#999;">查询失败，请重试</div>';
        }
    }
</script>
@endsection