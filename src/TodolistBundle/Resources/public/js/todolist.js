//General functions.
    /**
     * Sends an AJAX post request to the server, creates a popup object depending on the result and shows it to the user.
     *
     * @param string url
     * @param object parameters
     * @param string successText
     * @param sting failText
     * @return boolean
     */
    function sendPostRequestAndShowPopupWithResult(url, parameters, successText, failText) {
        var ajaxPost = $.post(url, parameters);
        var postSuccessful;
        ajaxPost.done(function (data) {
            var postSuccessFulPopup = new Popup('', successText, 'okButton');
            postSuccessFulPopup.show();

            if(url == '../delete-todo-item' || url == '../complete-todo-item') {
                console.log('pollo');
                removeTodoItemFromTheDomById(parameters.todoItemId);
            }

        });
        ajaxPost.fail(function (data) {
            var postNotSuccessfulPopup = new Popup('', failText, 'okButton');
            postNotSuccessfulPopup.show();
        });
    }

    /**
     * Adds an on click function by name to an array of nodes.
     *
     * @param Array nodes
     * @param string functionToExecute
     * @return void
     */
    function addClickListenersToElements(nodes, functionToExectute) {
        for (var i = 0; i < nodes.length; i += 1) {
            nodes[i].onclick = function (i) {
                return function (e) {
                    window[functionToExectute](e);
                };
            }(i);
        }
    }
//End general functions.

//To do list page.
    //when an item is successfully deleted on the back-end, it needs to be removed from the list.
    function removeTodoItemFromTheDomById(id) {
        console.log("#todo_item" + id);
        console.log($("#todo_item" + id));
        $("#todo_item" + id).remove();
    }
    //Add click listeners to the buttons.
    $(document).ready(function () {
        //get all button parent elements
        var completeButtons = $(".todo_item__complete_button");
        var deleteButtons = $(".todo_item__delete_button");

        addClickListenersToElements(completeButtons, 'completeItem');
        addClickListenersToElements(deleteButtons, 'deleteItem');
    });

    //Shows a confirm popup and sends an AJAX POST request to finalize a todo item.
    function completeItem(e) {
        console.log(1);
        var clicked = $(e.target);
        var itemId = clicked.attr('data-id');

        var completeItemPopup = new Popup('Item afronden', 'Weet u zeker dat u dit item wilt voltooien?', 'confirm', function () {
            var completeItemParameters = {'todoItemId': itemId};

            if (completeItemPopup.confirmed === true) {
                var postUrl = '../complete-todo-item';
                var deleteSuccessMessage = 'Het item is voltooid.';
                var deleteFailedMessage = 'Er is een fout opgetreden tijdens het opslaan van het item. Probeer het opnieuw alstublieft.';

                sendPostRequestAndShowPopupWithResult(postUrl, completeItemParameters, deleteSuccessMessage,  deleteFailedMessage);

            }
        });

        completeItemPopup.show();
    }

    //Shows a confirm popup and sends an AJAX POST request to delete a todo item.
    function deleteItem(e) {
        var clicked = $(e.target);
        var itemId = clicked.attr('data-id');

        var deleteItemPopup = new Popup('Item verwijderen', 'Weet u zeker dat u dit item wilt verwijderen?', 'confirm', function () {
            var deleteItemParameters = {'todoItemId': itemId};

            if (deleteItemPopup.confirmed === true) {
                var postUrl = '../delete-todo-item';
                var deleteSuccessMessage = 'Het item is verwijderd.';
                var deleteFailedMessage = 'Er is een fout opgetreden tijdens het opslaan van het item. Probeer het opnieuw alstublieft.';

                sendPostRequestAndShowPopupWithResult(postUrl, deleteItemParameters, deleteSuccessMessage,  deleteFailedMessage);
            }
        });
        deleteItemPopup.show();
    }
//End script for the to do list page.

//Script for the add-todo-item page.
    $(document).ready(function() {
        //Creates a new to do item.
        $('#create_todo_item').click(function(e) {
            e.preventDefault();

            //get all input values from the form
            var inputElements = jQuery('#add_todo_item :input');

            //Make an object of the values of the input elements.
            var createTodoItemParameters = {};
            inputElements.each(function () {
                if (!$(this).is('button')) {
                    createTodoItemParameters[this.name] = $(this).val();
                }
            });

            var createTodoItemUrl = '../add-todo-item';
            var createSuccessFulMessage = 'Het item is toegevoegd aan de lijst.';
            var createFailedMessage = 'Er is een fout opgetreden tijdens het opslaan van het item. Probeer het opnieuw alstublieft.';

            sendPostRequestAndShowPopupWithResult(createTodoItemUrl, createTodoItemParameters, createSuccessFulMessage,  createFailedMessage);
        });
    });