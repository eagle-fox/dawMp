package dto

type EmailDTO struct {
	LocalPart string `json:"localPart"`
	Domain    string `json:"domain"`
	Extension string `json:"extension"`
}

type UserDTO struct {
	ID              uint     `json:"id"`
	Nombre          string   `json:"nombre"`
	NombreSegundo   string   `json:"nombreSegundo"`
	ApellidoPrimero string   `json:"apellidoPrimero"`
	ApellidoSegundo string   `json:"apellidoSegundo"`
	Email           EmailDTO `json:"email"`
	Rol             string   `json:"rol"`
	CreatedAt       string   `json:"createdAt"`
	UpdatedAt       string   `json:"updatedAt"`
}

type IotDeviceDTO struct {
	ID            uint     `json:"id"`
	Token         string   `json:"token"`
	Name          string   `json:"name"`
	Especie       string   `json:"especie"`
	Cumpleanos    string   `json:"cumpleanos,omitempty"`
	Icon          string   `json:"icon"`
	User          UserDTO  `json:"user"`
	LastLatitude  *float64 `json:"lastLatitude,omitempty"`
	LastLongitude *float64 `json:"lastLongitude,omitempty"`
	CreatedAt     string   `json:"createdAt"`
	UpdatedAt     string   `json:"updatedAt"`
}

type IotDataDTO struct {
	ID        uint         `json:"id"`
	Device    IotDeviceDTO `json:"device"`
	Latitude  float64      `json:"latitude"`
	Longitude float64      `json:"longitude"`
	CreatedAt string       `json:"createdAt"`
	UpdatedAt string       `json:"updatedAt"`
}
