<style>
* {
  box-sizing: border-box;
}
.fab-wrapper {
    position: fixed;
    bottom: 3rem;
    right: 3rem;
    z-index: 999 !important;
}
.fab-checkbox {
    display: none;
}

.fab {
    position: absolute;
    bottom: -2rem;
    right: -2rem;
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    background: #1f3996;
    transition: all 0.3s ease;
    z-index: 1;
    border-bottom-right-radius: 6px;
    cursor: pointer;
    z-index: 999 !important;
}

.fab:before {
  content: "";
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.1);
}
.fab-checkbox:checked ~ .fab:before {
  width: 90%;
  height: 90%;
  left: 5%;
  top: 5%;
  background-color: rgba(255, 255, 255, 0.2);
}

.fab-dots {
  position: absolute;
  height: 8px;
  width: 8px;
  background-color: white;
  border-radius: 50%;
  top: 50%;
  transform: translateX(0%) translateY(-50%) rotate(0deg);
  opacity: 1;
  animation: blink 3s ease infinite;
  transition: all 0.3s ease;
}

.fab-dots-1 {
  left: 15px;
  animation-delay: 0s;
}
.fab-dots-2 {
  left: 50%;
  transform: translateX(-50%) translateY(-50%);
  animation-delay: 0.4s;
}
.fab-dots-3 {
  right: 15px;
  animation-delay: 0.8s;
}

.fab-checkbox:checked ~ .fab .fab-dots {
  height: 6px;
}

.fab .fab-dots-2 {
  transform: translateX(-50%) translateY(-50%) rotate(0deg);
}

.fab-checkbox:checked ~ .fab .fab-dots-1 {
  width: 32px;
  border-radius: 10px;
  left: 50%;
  transform: translateX(-50%) translateY(-50%) rotate(45deg);
}
.fab-checkbox:checked ~ .fab .fab-dots-3 {
  width: 32px;
  border-radius: 10px;
  right: 50%;
  transform: translateX(50%) translateY(-50%) rotate(-45deg);
}

@keyframes blink {
  50% {
    opacity: 0.25;
  }
}

.fab-checkbox:checked ~ .fab .fab-dots {
  animation: none;
}

.fab-wheel {
  position: absolute;
  bottom: 0;
  right: 0;
  border: 1px solid #;
  width: 10rem;
  height: 10rem;
  transition: all 0.3s ease;
  transform-origin: bottom right;
  transform: scale(0);
}

.fab-checkbox:checked ~ .fab-wheel {
  transform: scale(1);
}
.fab-action {
  position: absolute;
  background: #5569af;
  width: 3rem;
  height: 3rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff !important;
  transition: all 1s ease;
  opacity: 0;
}

.fab-checkbox:checked ~ .fab-wheel .fab-action {
  opacity: 1;
}

.fab-wheel .fab-action-1 {
  right: 535px;
  bottom: -25px;
}

.fab-wheel .fab-action-2 {
  right: 480px;
  bottom: -25px;
}
.fab-wheel .fab-action-3 {
  right: 425px;
  bottom: -25px;
}

.fab-wheel .fab-action-4 {
  right: 370px;
  bottom: -25px;
}

.fab-wheel .fab-action-5 {
  right: 315px;
  bottom: -25px;
}

.fab-wheel .fab-action-6 {
  right: 260px;
  bottom: -25px;
}

.fab-wheel .fab-action-7 {
  right: 205px;
  bottom: -25px;
}
.fab-wheel .fab-action-8 {
  right: 150px;
  bottom: -25px;
}

.fab-wheel .fab-action-9 {
  right: 95px;
  bottom: -25px;
}

.fab-wheel .fab-action-10 {
  right: 40px;
  bottom: -25px;
}

.fab-actionx {
  background: #31a0ab !important;
}

.fab-wheel i {
    font-size: 1.4em;
}

</style>
<div class="fab-wrapper">
    <input id="fabCheckbox" type="checkbox" class="fab-checkbox" />
    <label class="fab" for="fabCheckbox">
        <span class="fab-dots fab-dots-1"></span>
        <span class="fab-dots fab-dots-2"></span>
        <span class="fab-dots fab-dots-3"></span>
    </label>
    <div class="fab-wheel">
        <!-- <a href="javascript:;" data="collection" class="fab-action fab-actionx fab-action-1 preview-voucher-btn" title="Preview Collection Voucher" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="la la-file-pdf-o"></i>
        </a>
        <a href="javascript:;" data="payables" class="fab-action fab-actionx fab-action-2 preview-voucher-btn" title="Preview Payables Voucher" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="la la-file-o"></i>
        </a>
        <a href="javascript:;" data="cash" class="fab-action fab-actionx fab-action-3 preview-voucher-btn" title="Preview Cash Voucher" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="la la-file-powerpoint-o"></i>
        </a> -->
        <a href="javascript:;" data="cheque" class="fab-action fab-actionx fab-action-4 preview-voucher-btn" title="Preview Cheque Voucher" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="la la-file-text"></i>
        </a>
        <a href="javascript:;" data="others" class="fab-action fab-actionx fab-action-5 preview-voucher-btn" title="Preview Others Voucher" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="la la-file-image-o"></i>
        </a>
        <!-- <a href="javascript:;" data="collection" class="fab-action fab-action-6 print-voucher-btn" title="Prepare Collection Voucher" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="la la-file-pdf-o"></i>
        </a>
        <a href="javascript:;" data="payables" class="fab-action fab-action-7 print-voucher-btn" title="Prepare Payables Voucher" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="la la-file-o"></i>
        </a>
        <a href="javascript:;" data="cash" class="fab-action fab-action-8 print-voucher-btn" title="Prepare Cash Voucher" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="la la-file-powerpoint-o"></i>
        </a> -->
        <a href="javascript:;" data="cheque" class="fab-action fab-action-9 print-voucher-btn" title="Prepare Cheque Voucher" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="la la-file-text"></i>
        </a>
        <a href="javascript:;" data="others" class="fab-action fab-action-10 print-voucher-btn" title="Prepare Others Voucher" data-bs-toggle="tooltip" data-bs-placement="top">
            <i class="la la-file-image-o"></i>
        </a>
    </div>
</div>