# Alternativas de Mapas Gratuitos para Eventos

## Implementação Atual: OpenStreetMap + Leaflet ✅

A solução implementada usa:
- **OpenStreetMap**: Dados de mapa gratuitos e de código aberto
- **Leaflet**: Biblioteca JavaScript para mapas interativos
- **Nominatim**: Serviço de geocodificação gratuito

### Vantagens:
- ✅ Totalmente gratuito
- ✅ Sem limites de requisições
- ✅ Dados de código aberto
- ✅ Funciona offline (com cache)
- ✅ Personalizável

### Desvantagens:
- ⚠️ Geocodificação pode ser menos precisa que Google Maps
- ⚠️ Interface visual mais simples

---

## Outras Alternativas Gratuitas

### 1. Mapbox (Gratuito até 50.000 visualizações/mês)
```html
<!-- CSS -->
<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
<!-- JavaScript -->
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
```

### 2. HERE Maps (Gratuito até 250.000 transações/mês)
```html
<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
```

### 3. CartoDB (Gratuito para projetos pequenos)
```html
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
```

### 4. Stadia Maps (Gratuito até 10.000 visualizações/mês)
```html
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

---

## Como Implementar Outras Alternativas

### Exemplo com Mapbox:
```javascript
// Substituir no arquivo event.blade.php
mapboxgl.accessToken = 'SEU_TOKEN_AQUI';
const map = new mapboxgl.Map({
    container: 'map_canvas',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [longitude, latitude],
    zoom: 15
});

new mapboxgl.Marker()
    .setLngLat([longitude, latitude])
    .addTo(map);
```

### Exemplo com HERE Maps:
```javascript
// Substituir no arquivo event.blade.php
const platform = new H.service.Platform({
    apikey: 'SUA_CHAVE_AQUI'
});

const geocoder = platform.getGeocodingService();
geocoder.geocode({
    searchText: endereco
}, (result) => {
    // Implementar lógica do mapa
});
```

---

## Recomendação

**Use a implementação atual (OpenStreetMap + Leaflet)** porque:

1. **É 100% gratuita** sem limites
2. **Não requer chaves de API**
3. **Funciona imediatamente**
4. **É confiável e estável**
5. **Tem boa comunidade de suporte**

Se precisar de mais precisão na geocodificação, considere:
- Armazenar coordenadas no banco de dados
- Usar um serviço de geocodificação pago apenas para o backend
- Implementar cache de coordenadas

---

## Melhorias Futuras

1. **Cache de coordenadas**: Armazenar lat/lng no banco de dados
2. **Múltiplos provedores**: Fallback entre diferentes serviços
3. **Mapas offline**: Cache de tiles para uso offline
4. **Personalização**: Temas e estilos customizados
5. **Interatividade**: Rotas, direções, etc. 