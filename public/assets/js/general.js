const sendRequestToAPI = async (typeRequest, endpoint, data = null) => {
    try {
        const response = await fetch(`/${endpoint}`, {
            method:     typeRequest,
            body:       data ? JSON.stringify({ data: data }) : null,
            headers: { 
                'Content-Type':     'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

       return response.json() ?? false;
    } catch (error) {
        return false;
    } 
}

const setAlert = ({ element, message, color, closeInSeconds = false }) => {

    closeAlert( element );

    element.text(message);
    element.removeClass();
    element.addClass(`alert alert-${color} alert-dismissible text-center`);

    if (closeInSeconds) {
        setTimeout(() => { closeAlert( element ) }, closeInSeconds * 1000);
    }
}

const closeAlert = ( element ) => {
    element.text('');
    element.removeClass();
    element.addClass('d-none');
}