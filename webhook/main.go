package main

import (
	"log"
	"net/http"
	"os"
	"os/exec"
)

func pushHandler(w http.ResponseWriter, r *http.Request) {
	// secret := r.Header.Get("X-Hub-Signature")
	// if secret != "sha1="+secretKey {
	// 	http.Error(w, "No autorizado", http.StatusUnauthorized)
	// 	return
	// }

	if r.Method != http.MethodPost {
		http.Error(w, "Método no permitido", http.StatusMethodNotAllowed)
		return
	}

	err := r.ParseForm()
	if err != nil {
		http.Error(w, "Error al analizar el formulario", http.StatusBadRequest)
		return
	}

	// Obtener credenciales de autenticación para el repositorio privado
	gitUsername := os.Getenv("GIT_USERNAME")
	gitToken := os.Getenv("GIT_TOKEN")

	// Verificar que las credenciales estén definidas
	if gitUsername == "" || gitToken == "" {
		log.Println("Error: Debes definir GIT_USERNAME y GIT_TOKEN para autenticar en el repositorio privado.")
		http.Error(w, "Error de autenticación", http.StatusInternalServerError)
		return
	}

	// Ejecutar el git pull en el repositorio privado
	cmd := exec.Command("sh", "-c", "cd  /go/src/app/dawMp && git pull")
	if err := cmd.Run(); err != nil {
		log.Println("Error: ", err)
		http.Error(w, "Error al ejecutar el git pull", http.StatusInternalServerError)
		return
	}

	return

}

func main() {
	log.SetOutput(os.Stdout)

	http.HandleFunc("/push", pushHandler)
	log.Fatal(http.ListenAndServe(":2002", nil))
}
