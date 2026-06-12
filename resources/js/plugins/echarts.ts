import { use } from 'echarts/core';

import { PieChart, BarChart, LineChart } from 'echarts/charts';

import {
    TooltipComponent,
    LegendComponent,
    GridComponent,
    TitleComponent,
} from 'echarts/components';

import { CanvasRenderer } from 'echarts/renderers';

use([
    PieChart,
    BarChart,
    LineChart,

    TooltipComponent,
    LegendComponent,
    GridComponent,
    TitleComponent,

    CanvasRenderer,
]);
