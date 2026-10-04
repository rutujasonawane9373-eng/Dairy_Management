import { renderToString } from 'react-dom/server'
import App from './App.jsx'

const html = renderToString(<App />)
console.log('RENDER_OK length=' + html.length)
console.log(html)