package types

import (
	"encoding/json"
	"fmt"
	"net"
)

type IPv4 struct {
	ipv4 uint32
}

func NewIPv4(ipv4 interface{}) (*IPv4, error) {
	switch v := ipv4.(type) {
	case *IPv4:
		return v, nil
	case string:
		ip := net.ParseIP(v)
		if ip == nil {
			return nil, fmt.Errorf("invalid argument type for IPv4 address")
		}
		return &IPv4{ipv4: ipToUint32(ip.To4())}, nil
	case int:
		return &IPv4{ipv4: uint32(v)}, nil
	default:
		return nil, fmt.Errorf("invalid argument type for IPv4 address")
	}
}

func (ip *IPv4) MarshalJSON() ([]byte, error) {
	return json.Marshal(ip.String())
}

func (ip *IPv4) String() string {
	return fmt.Sprintf("%d.%d.%d.%d", byte(ip.ipv4>>24), byte(ip.ipv4>>16), byte(ip.ipv4>>8), byte(ip.ipv4))
}

func ipToUint32(ip net.IP) uint32 {
	return uint32(ip[0])<<24 + uint32(ip[1])<<16 + uint32(ip[2])<<8 + uint32(ip[3])
}
