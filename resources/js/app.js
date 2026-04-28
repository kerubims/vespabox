import '../css/app.css';
import './echo';
import Chart from 'chart.js/auto';
import mermaid from 'mermaid';

window.Chart = Chart;
mermaid.initialize({ startOnLoad: true });
