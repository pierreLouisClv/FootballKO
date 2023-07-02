$(document).ready(function() {
    $('#championship_player').on('change', function() {
        var championship_player_slug = $(this).val();

        // Effectuer une requête AJAX pour récupérer les clubs en fonction du championnat sélectionné
        $.ajax({
            url: '/signing/add/championship_selected', // L'URL doit être adaptée à ton contrôleur
            method: 'GET',
            data: { championshipSlug: championship_player_slug },
            success: function(response) {
                // Mettre à jour les options du sélecteur de clubs
                $('#club_player').html(response);
            }
        });
    });

    $('#club_player').on('change', function() {
        var club_player_slug = $(this).val();

        // Effectuer une requête AJAX pour récupérer les joueurs en fonction du club sélectionné
        $.ajax({
            url: '/signing/add/club_selected', // L'URL doit être adaptée à ton contrôleur
            method: 'GET',
            data: { clubSlug: club_player_slug },
            success: function(response) {
                // Mettre à jour les options du sélecteur de joueurs
                $('#player').html(response);
            }
        });
    });

    $('#championship_club_left').on('change', function() {
        var championship_club_left_slug = $(this).val();

        // Effectuer une requête AJAX pour récupérer les clubs en fonction du championnat sélectionné
        $.ajax({
            url: '/signing/add/championship_selected',
            method: 'GET',
            data: { championshipSlug: championship_club_left_slug },
            success: function(response) {
                // Mettre à jour les options du sélecteur de clubs
                $('#club_left').html(response);
            }
        });
    });

    $('#championship_club_joined').on('change', function() {
        var championship_club_joined_slug = $(this).val();
        console.log(championship_club_joined_slug);
        // Effectuer une requête AJAX pour récupérer les clubs en fonction du championnat sélectionné
        $.ajax({
            url: '/signing/add/championship_selected', // L'URL doit être adaptée à ton contrôleur
            method: 'GET',
            data: { championshipSlug: championship_club_joined_slug },
            success: function(response) {
                // Mettre à jour les options du sélecteur de
                console.log(response);
                $('#club_joined').html(response);
            }
        });
    });
});