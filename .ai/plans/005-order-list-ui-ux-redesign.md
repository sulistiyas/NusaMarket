# Plan 005: Order List UI/UX Redesign

## Objective
Redesign the Order List (`pages.orders.index`) to incorporate a modern Blue Ocean design system, dynamic stat summary strip, role segmented control, status filter pills with badge counts, search & sort toolbar, status-colored left border cards, round product thumbnails, Alpine.js collapsible product list, and responsive layout under 980px.

## Scope & Components
1. **OrderService & OrderController**:
   - Add status counts calculation for total, pending, processing, completed, cancelled.
   - Support search and sort filters in `getUserOrders`.
2. **Layout & Icons**:
   - Include Remix Icon CDN in `layouts/app.blade.php`.
3. **Views**:
   - Update `pages/orders/index.blade.php` with summary cards, segmented control, status pills, search/sort toolbar, and card redesign with Alpine.js collapsible items.
4. **CSS**:
   - Update `public/css/order.css` with card borders, thumbnail circles, stat cards, segmented control, and responsive breakpoint (<980px).

## Verification
- Syntax checking with PHP.
- Visual inspection in browser subagent.
