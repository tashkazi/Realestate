function fetchAndDisplayPropertyDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const propertyID = urlParams.get('propertyID');

    $.ajax({
        url: 'getpropertydetails.php',
        method: 'GET',
        data: { propertyID: propertyID },
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                console.log('Property details fetched successfully!');
                updatePropertyDetails(data.property);
            } else {
                console.error('Error fetching property details:', data.error);
            }
        },
        error: function (xhr, status, error) {
            console.error('Failed to fetch property details:', error);
        }
    });
}

function updatePropertyDetails(property) {
    const propertyDetails = $('#propertyDetails');

    const propertyHtml = `
        <h3>${property.PropertyName}</h3>
        <img src="${property.ImagePaths}" alt="${property.PropertyName}">
        <form id="updateForm">
       <input type="hidden" id="propertyID" value="${property.PropertyID}">
        
            <label for="propertyName">Property Name:</label>
            <input type="text" id="propertyName" name="propertyName" value="${property.PropertyName}" required>

            <label for="propertyType">Property Type:</label>
            <input type="text" id="propertyType" name="propertyType" value="${property.PropertyType}" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="${property.Location}" required>

            <label for="size">Size:</label>
            <input type="text" id="size" name="size" value="${property.Size}" required>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" value="${property.Price}" required>

            <label for="yearToBuild">Year To Build:</label>
            <input type="text" id="yearToBuild" name="yearToBuild" value="${property.YearToBuild}" required>

            <label for="contactRealtor">Contact Realtor:</label>
            <input type="text" id="contactRealtor" name="contactRealtor" value="${property.ContactRealtor}" required>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="Available" ${property.Status === 'Available' ? 'selected' : ''}>Available</option>
                <option value="Pending" ${property.Status === 'Pending' ? 'selected' : ''}>Pending</option>
                <option value="Sold" ${property.Status === 'Sold' ? 'selected' : ''}>Sold</option>
            </select>
            
            <button type="button" id="saveButton">Save</button>
            <button type="button" id="deleteButton">Delete</button>
        </form>
    `;

    propertyDetails.html(propertyHtml);
}
$(document).on('click', '#saveButton', function () {
    const propertyData = {
        propertyID: $('#propertyID').val(),
        propertyName: $('#propertyName').val(),
        propertyType: $('#propertyType').val(),
        location: $('#location').val(),
        size: $('#size').val(),
        price: $('#price').val(),
        yearToBuild: $('#yearToBuild').val(),
        contactRealtor: $('#contactRealtor').val(),
        status: $('#status').val(),
        updateProperty: true
    };

    updateProperty(propertyData);
});


if (typeof updateProperty === 'undefined') {

    function updateProperty(propertyData) {
        const url = 'manageproperties.php';

        $.ajax({
            url: url,
            method: 'POST',
            data: propertyData,
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    alert('Property updated successfully!');

                    window.location.href = 'adminhomepage.html';
                } else {
                    console.error('Error updating property:', data.error);

                    alert('Error updating property: ' + data.error);
                }
            },
            error: function (xhr, status, error) {
                console.error('Failed to update property:', error);

                alert('Failed to update property: ' + error);
            }
        });
    }


}

$(document).on('click', '#saveButton', function () {
    console.log('Save button clicked!');

    const propertyData = {
        propertyID: $('#propertyID').val(),
        propertyName: $('#propertyName').val(),
        propertyType: $('#propertyType').val(),
        location: $('#location').val(),
        size: $('#size').val(),
        price: $('#price').val(),
        yearToBuild: $('#yearToBuild').val(),
        contactRealtor: $('#contactRealtor').val(),
        status: $('#status').val(),
        updateProperty: true
    };

    console.log('Property Data:', propertyData);

    updateProperty(propertyData);
});

$(document).on('click', '#deleteButton', function () {
    var propertyID = $('#propertyID').val();

    if (propertyID) {
        deleteProperty(propertyID);
    } else {
        console.error('PropertyID is not available.');
    }
});

function deleteProperty(propertyID) {
    $.ajax({
        url: 'deleteproperty.php',
        method: 'POST',
        data: { propertyID: propertyID },
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                console.log('Property deleted successfully!');

                window.location.href = 'adminhomepage.html';
            } else {
                console.error('Error deleting property:', data.error);

                if (data.error === 'Unauthorized action!') {
                    alert('Unauthorized action! You do not have permission to delete this property.');
                } else {
                    alert('Error deleting property: ' + data.error);
                }
            }
        },
        error: function (xhr, status, error) {
            console.error('Failed to delete property:', error);
        }
    });
}



