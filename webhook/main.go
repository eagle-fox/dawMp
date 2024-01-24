package main

import (
	"fmt"
	"net/http"
	"os/exec"
)

const (
	secretKey = "59bac06c583efb070a7c2f16c6a28ed4584ac07209068c132dec75f2a0e1be1d" // Reemplaza con tu secreto compartido
)

func pushHandler(w http.ResponseWriter, r *http.Request) {
	// Verifica la autenticidad usando el secreto
	secret := r.Header.Get("X-Hub-Signature")
	if secret != "sha1="+secretKey {
		http.Error(w, "No autorizado", http.StatusUnauthorized)
		return
	}

	// Verifica el método de la solicitud
	if r.Method != http.MethodPost {
		http.Error(w, "Método no permitido", http.StatusMethodNotAllowed)
		return
	}

	// Accede a los datos como formulario codificado en URL
	err := r.ParseForm()
	if err != nil {
		http.Error(w, "Error al analizar el formulario", http.StatusBadRequest)
		return
	}

	// Realiza acciones según la lógica de tu aplicación
	// Aquí ejecutamos el comando Docker Compose para recrear
	cmd := exec.Command("docker-compose", "up", "--force-recreate", "--build", "-d")
	err = cmd.Run()
	if err != nil {
		http.Error(w, "Error al recrear Docker Compose", http.StatusInternalServerError)
		return
	}

	// Responde a la solicitud
	fmt.Fprint(w, "Acción de recrear Docker Compose completada exitosamente")

	// realiza a nivel de OS un  git fetch && git pull --force

	cmd = exec.Command("git", "fetch")
	err = cmd.Run()
	if err != nil {
		http.Error(w, "Error al hacer git fetch", http.StatusInternalServerError)
		return
	}

	fmt.Fprint(w, "git fetch completado exitosamente")

}

func main() {
	http.HandleFunc("/push", pushHandler)
	http.ListenAndServe(":2003", nil)
}
