/* eventually this module should work by:
    - taking args (elementToSelect, dataToRender)
    - dataToRender will be passed from php MySQL query
    - this will require refactoring fileend.php into a fcn
*/
function drawPieChart(container, jsonData, colorData, d3 = window.d3) {
    console.log(jsonData, colorData);
    const w = '200';
    const h = '200';
    const r = Math.min(w, h)/2;
    
    // append color scheme to jsonData
    // for (let obj of jsonData) {
    //     obj.color = colorData[obj.label] || colorData[jsonData.indexOf(obj)];
    // }
    const color = d3.scaleOrdinal(Object.values(colorData));
    
    const svg = d3.select(container)
        .append('svg')
        .attr('width', w)
        .attr('height', h)
        .append('g')
        .attr('transform', `translate(${w/2},${h/2})`)
        
    const arc = d3.arc()
        .innerRadius(0)
        .outerRadius(r)
        
    const pie = d3.pie()
        .value(d => d.value)
        .sort(null)
        
    const arcs = svg.selectAll('g.slice')
        .data(pie(jsonData))
        .enter()
        .append('g')
        .attr('class', 'slice');
        
    arcs.append('path')
        .attr('d', arc)
        .attr('fill', d => {
            console.log(d.data);
            return color(d.data.label);
        })
        
    arcs.append('text')
        .attr('transform', d => {
            d.innerRadius = 0;
            d.outerRadius = r;
            return `translate(${arc.centroid(d)})`;
        })
        .attr('text-anchor', 'middle')
        .text((d, i) => i + 1);
        
    drawLegend(container, jsonData, Object.values(colorData));
}

function drawLegend(container, data, colorScheme) {
    var legend = container.nextElementSibling

    data.forEach((datum, i) => {
        const label = legend.appendChild(document.createElement('span'))
        const swatch = document.createElement('i')
        
        label.classList.add('legend-label')
        label.textContent = datum.label

        swatch.innerText = i + 1;
        swatch.classList.add('legend-swatch')
        swatch.style.backgroundColor = colorScheme[i]
        label.insertAdjacentElement('afterbegin', swatch)
    })
}
