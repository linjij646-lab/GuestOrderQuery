# 🚀 Laravel 订单自助查询系统 (Order Tracking System)

本项目是一款专为电商/零售网站打造的**极简高端**订单自助查询插件。基于 Laravel 框架开发，采用前后端分离逻辑，旨在为用户提供无需登录、安全且优雅的订单追踪体验。

---

## ✨ 核心特性 (Features)

### 1. 💎 高端视觉设计 (High-End UI/UX)
* **极简主义风格**：采用黑白灰高阶配色，强调留白感与呼吸感，对标国际一线奢侈品官网视觉逻辑。
* **交互动画**：结果展示采用 `Cubic-Bezier` 曲线动画，实现丝滑的滑入加载效果。
* **响应式适配**：完美适配 PC、平板及手机移动端，针对手机操作优化了按钮触感与布局。

### 2. 🔍 多维度快捷查询 (Multi-Dimensional Query)
* 支持多种预留信息找回订单：
    * **姓名查询**：适配中文姓名脱敏展示。
    * **手机号查询**：支持模糊/精确匹配（根据后端逻辑）。
    * **订单号查询**：直接定位唯一订单。
    * **邮箱查询**：适配海外及通用用户习惯。

### 3. 🛡️ 隐私保护机制 (Privacy Shield)
* **字段脱敏**：前端展示自动适配后端传输的掩码数据（如：`138****8000`、`沉*`），确保用户信息不被恶意爬取。
* **安全防御**：集成 CSRF 令牌校验，防止跨站请求伪造攻击。

### 4. 📦 深度追踪系统 (Order History)
* **时间轴动态**：可视化展示订单从“待支付”到“已完成”的全生命周期。
* **客服备注同步**：实时同步后台 `order_histories` 中的变更备注（如查件网址、物流单号）。

---

## 🛠️ 技术栈 (Tech Stack)

* **Backend**: Laravel / PHP 8.x
* **Frontend**: Tailwind-like CSS Architecture / Vanilla JS (No jQuery)
* **Database**: MySQL (Table: `addresses`, `order_histories`, etc.)
* **Security**: CSRF Protection / Data Masking

---

## 🚀 快速开始 (Quick Start)

1.  **文件部署**：
    将 `query_form.blade.php` 放置于你的 Laravel 项目 `resources/views/` 目录下。
    
2.  **路由配置**：
    确保后端定义了名为 `guest_order_query.search` 的路由并指向查询 Controller。

3.  **字段对照**：
    本 UI 已完美适配以下字段：
    * `number`: 订单编号
    * `name`: 收货人姓名
    * `phone`: 联系电话
    * `address`: 收货地址
    * `total`: 订单总金额（已适配金额符号合并）
    * `histories`: 订单追踪动态数组

---

## 📸 预览 (UI Preview)

* **Desktop**: 极简卡片式布局，高对比度文字排版。
* **Mobile**: 纵向堆叠式信息流，大触控面积按钮。

---

## ⚖️ 开源协议 (License)
MIT License.