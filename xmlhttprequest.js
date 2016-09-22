

function getXmlHttpRequest()
{

	if (window.XMLHttpRequest){
            try{
                return new XMLHttpRequest();
            } catch (e){};
        }else if (window.ActiveXObject){
            try {
                return new ActiveXObject('Microsoft.XMLHTTP');
            }catch (e){}
        }
    return null;
}

