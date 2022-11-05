<?php 

namespace cgc\platform\libs;


class Message 
{

        public function success(string $message)
        {
            
            if($message == 'insert') $message = 'insére ave success';
            if($message == 'updated') $message = 'modifié avec succès';
            if($message == 'deleted') $message ='supprimé avec succès';

                echo '
                <script>
                Toastify({
                    text: "'.$message.'",
                    duration: 3000,
                    position: "right", // `left`, `center` or `right`
                    backgroundColor: "#FEB139",
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    onClick: function () { } // Callback after click
                  }).showToast();
                  </script>
                ';
        
        }


        public function error(string $message)
        {
            if($message == 'insert') $message = "erreur lors de l'insertion";
            if($message == 'updated') $message = 'erreur sur la mise à jour';
            if($message == 'deleted') $message ='erreur sur la suppression';
            if($message == 'server_error') $message ='Erreur de serveur, veuillez essayer plus tard';
                echo '
                <script>
                Toastify({
                    text: "'.$message.'",
                    duration: 3000,
                    position: "right", // `left`, `center` or `right`
                    backgroundColor: "#E91E63",
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    onClick: function () { } // Callback after click
                  }).showToast();
                  </script>
                ';
        }


        public function CustomError(string $message)
        {
                echo '
                <script>
                Toastify({
                    text: "'.$message.'",
                    duration: 3000,
                    position: "right", // `left`, `center` or `right`
                    backgroundColor: "#E91E63",
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    onClick: function () { } // Callback after click
                  }).showToast();
                  </script>
                ';
        }



}