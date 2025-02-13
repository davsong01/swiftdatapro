<div id="responses" style="text-align: left;">
    <span>
        Available Balance. <br />
        <span class="hiddenBalance" style="color: #940798; font-weight: bold; font-size: 20px;">
            ****
        </span>
        <span class="balance" style="color: #940798; font-weight: bold; font-size: 20px; display: none;">
            {{ settings()->currency . number_format($balance, 2) }}
        </span>
        <i class="toggleBalance fa fa-eye-slash" style="cursor: pointer; margin-left: 10px;"></i>
    </span>
</div>
