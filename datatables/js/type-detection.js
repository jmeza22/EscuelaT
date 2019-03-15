jQuery.fn.dataTableExt.aTypes.push(
    function ( sData )
    {
        var sValidChars = "0123456789.-";
        var Char;
         
        /* Check the numeric part */
        for ( i=1 ; i<sData.length ; i++ )
        {
            Char = sData.charAt(i);
            if (sValidChars.indexOf(Char) == -1)
            {
                return null;
            }
        }
         
        /* Check prefixed by currency */
        if ( sData.charAt(0) == '$' || sData.charAt(0) == '£' )
        {
            return 'currency';
        }
        return null;
    }
);