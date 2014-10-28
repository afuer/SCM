$(document).ready(function(){
            
                        
    $('.tax').change(function(){

        var TaxPercent, TaxAmount, budget;
        budget=$('.BillAmount').val();
        TaxPercent=$(this).val();
        TaxAmount=parseFloat((budget*TaxPercent)/100).toFixed(2);
                   
        $(this).parents('tr').find('input.tax_amount').val(TaxAmount);
                
    });
            
    $('.vat').change(function(){
        var VATPercent, VATAmount, budget;
        budget=$('.BillAmount').val();
        VATPercent=$(this).val();
        VATAmount=parseFloat((budget*VATPercent)/100).toFixed(2);
                   
        $(this).parents('tr:first').find('input.vat_amount').val(VATAmount);

    //recalc();
                
    });
            
    $('.TaxVATStatus').change(function(){
            
            
        var TaxAmount, VATAmount,PayableAmount, BillAmount;

        if($('.TaxVATStatus').val()==2) // i.e. TAX & VAT included..
        {
            PayableAmount=$(this).parents('tr:first').find('input.BillAmount').val();
            $(this).parents('tr').find('input.PayableAmount').val(PayableAmount);
        }
                    
        if($('.TaxVATStatus').val()==1) // i.e. TAX & VAT included..
        {
            TaxAmount=$(this).parents('tr').find('input.tax_amount').val();
            VATAmount=$(this).parents('tr').find('input.vat_amount').val();
            PayableAmount=$(this).parents('tr:first').find('input.BillAmount').val();
            TotalAmount=parseFloat(PayableAmount)-parseFloat(VATAmount)-parseFloat(TaxAmount);
            $(this).parents('tr').find('input.PayableAmount').val(TotalAmount);
                
        }
            
        if($('.TaxVATStatus').val()==3) // i.e. TAX & VAT exclude..
        {
            TaxAmount=$(this).parents('tr').find('input.tax_amount').val();
            VATAmount=$(this).parents('tr').find('input.vat_amount').val();
            PayableAmount=$(this).parents('tr:first').find('input.BillAmount').val();
            TotalAmount=parseFloat(PayableAmount)+parseFloat(VATAmount)+parseFloat(TaxAmount);
            $(this).parents('tr').find('input.PayableAmount').val(TotalAmount);
                
        }
            
        if($('.TaxVATStatus').val()==4) // i.e. TAX & VAT exclude..
        {
            TaxAmount=$(this).parents('tr').find('input.tax_amount').val();
            VATAmount=$(this).parents('tr').find('input.vat_amount').val();
            PayableAmount=$(this).parents('tr:first').find('input.BillAmount').val();
            TotalAmount=parseFloat(PayableAmount)+parseFloat(VATAmount)+parseFloat(TaxAmount);
            $(this).parents('tr').find('input.PayableAmount').val(TotalAmount);
                
        }
                
    });
        
});