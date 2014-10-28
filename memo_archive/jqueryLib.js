     $('#AMOUNT').change(function() {
            var claimAmnt=$('#AMOUNT').val();
            var vat=$('#VAT').val();
            var tax=$('#TAX').val();
            var advAmnt=$('#ADV_AMNT').val();
            var scrMoney=$('#SCR_MONEY').val();
            var pnltAmount = $('#PNLT_AMNT').val();
            vat==''? vat=0 : vat=vat;
            tax==''? tax=0 : tax=tax;
            advAmnt==''? advAmnt=0 : advAmnt=advAmnt;
            scrMoney==''? scrMoney=0 : scrMoney=scrMoney;
            pnltAmount==''? pnltAmount=0 : pnltAmount=pnltAmount;
            
            $('#PAY_AMOUNT').val(claimAmnt-vat-tax-advAmnt-scrMoney-pnltAmount);
        });
        
        
        $('#VAT').change(function() {
            var claimAmnt=$('#AMOUNT').val();
            var vat=$('#VAT').val();
            var tax=$('#TAX').val();
            var advAmnt=$('#ADV_AMNT').val();
            var scrMoney=$('#SCR_MONEY').val();
            var pnltAmount = $('#PNLT_AMNT').val();
            vat==''? vat=0 : vat=vat;
            tax==''? tax=0 : tax=tax;
            advAmnt==''? advAmnt=0 : advAmnt=advAmnt;
            scrMoney==''? scrMoney=0 : scrMoney=scrMoney;
            pnltAmount==''? pnltAmount=0 : pnltAmount=pnltAmount;
            
            $('#PAY_AMOUNT').val(claimAmnt-vat-tax-advAmnt-scrMoney-pnltAmount);
        });
        
        
        $('#TAX').change(function() {
            var claimAmnt=$('#AMOUNT').val();
            var vat=$('#VAT').val();
            var tax=$('#TAX').val();
            var advAmnt=$('#ADV_AMNT').val();
            var scrMoney=$('#SCR_MONEY').val();
            var pnltAmount = $('#PNLT_AMNT').val();
            vat==''? vat=0 : vat=vat;
            tax==''? tax=0 : tax=tax;
            advAmnt==''? advAmnt=0 : advAmnt=advAmnt;
            scrMoney==''? scrMoney=0 : scrMoney=scrMoney;
            pnltAmount==''? pnltAmount=0 : pnltAmount=pnltAmount;
            
            $('#PAY_AMOUNT').val(claimAmnt-vat-tax-advAmnt-scrMoney-pnltAmount);
        });
        
        $('#ADV_AMNT').change(function() {
            var claimAmnt=$('#AMOUNT').val();
            var vat=$('#VAT').val();
            var tax=$('#TAX').val();
            var advAmnt=$('#ADV_AMNT').val();
            var scrMoney=$('#SCR_MONEY').val();
            var pnltAmount = $('#PNLT_AMNT').val();
            vat==''? vat=0 : vat=vat;
            tax==''? tax=0 : tax=tax;
            advAmnt==''? advAmnt=0 : advAmnt=advAmnt;
            scrMoney==''? scrMoney=0 : scrMoney=scrMoney;
            pnltAmount==''? pnltAmount=0 : pnltAmount=pnltAmount;
            
            $('#PAY_AMOUNT').val(claimAmnt-vat-tax-advAmnt-scrMoney-pnltAmount);
        });
        
        $('#SCR_MONEY').change(function() {
            var claimAmnt=$('#AMOUNT').val();
            var vat=$('#VAT').val();
            var tax=$('#TAX').val();
            var advAmnt=$('#ADV_AMNT').val();
            var scrMoney=$('#SCR_MONEY').val();
            var pnltAmount = $('#PNLT_AMNT').val();
            vat==''? vat=0 : vat=vat;
            tax==''? tax=0 : tax=tax;
            advAmnt==''? advAmnt=0 : advAmnt=advAmnt;
            scrMoney==''? scrMoney=0 : scrMoney=scrMoney;
            pnltAmount==''? pnltAmount=0 : pnltAmount=pnltAmount;
            
            $('#PAY_AMOUNT').val(claimAmnt-vat-tax-advAmnt-scrMoney-pnltAmount);
        });
        
         $('#PNLT_AMNT').change(function() {
            var claimAmnt=$('#AMOUNT').val();
            var vat=$('#VAT').val();
            var tax=$('#TAX').val();
            var advAmnt=$('#ADV_AMNT').val();
            var scrMoney=$('#SCR_MONEY').val();
            var pnltAmount = $('#PNLT_AMNT').val();
            vat==''? vat=0 : vat=vat;
            tax==''? tax=0 : tax=tax;
            advAmnt==''? advAmnt=0 : advAmnt=advAmnt;
            scrMoney==''? scrMoney=0 : scrMoney=scrMoney;
            pnltAmount==''? pnltAmount=0 : pnltAmount=pnltAmount;
            
            $('#PAY_AMOUNT').val(claimAmnt-vat-tax-advAmnt-scrMoney-pnltAmount);
        });