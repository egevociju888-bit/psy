window.GzBreathAudio = (() => {
  'use strict';
const AudioKit = (() => {
  const AC = window.AudioContext || window.webkitAudioContext;
  let ctx = null, out = null, noiseBuf = null;

  function ensure() {
    if (!AC) return null;
    if (!ctx) {
      try { ctx = new AC(); } catch (e) { return null; }
      out = ctx.createDynamicsCompressor();
      out.threshold.value = -20; out.knee.value = 18; out.ratio.value = 4;
      out.connect(ctx.destination);
    }
    return ctx;
  }

  function noise() {
    if (noiseBuf) return noiseBuf;
    const len = ctx.sampleRate * 4;
    noiseBuf = ctx.createBuffer(1, len, ctx.sampleRate);
    const d = noiseBuf.getChannelData(0);
    let last = 0;
    for (let i = 0; i < len; i++) {
      last = (last + 0.02 * (Math.random() * 2 - 1)) / 1.02;
      d[i] = last * 3.5;
    }
    return noiseBuf;
  }
  function panner(p) {
    if (ctx.createStereoPanner) { const n = ctx.createStereoPanner(); n.pan.value = p; return n; }
    return ctx.createGain();
  }
  function envelope(g, t, peak, attack, dur) {
    g.gain.setValueAtTime(0.0001, t);
    g.gain.exponentialRampToValueAtTime(peak, t + attack);
    g.gain.exponentialRampToValueAtTime(0.0001, t + dur);
  }
  function fadeBus(bus, to, sec) {
    const t = ctx.currentTime;
    bus.gain.cancelScheduledValues(t);
    bus.gain.setValueAtTime(bus.gain.value, t);
    bus.gain.linearRampToValueAtTime(to, t + sec);
  }

  function relax() {
    const bus = ctx.createGain();
    bus.gain.value = 0; bus.connect(out);
    fadeBus(bus, .8, 2);
    const lp = ctx.createBiquadFilter();
    lp.type = 'lowpass'; lp.frequency.value = 600; lp.Q.value = .4; lp.connect(bus);
    const sources = [];
    [110, 164.81, 220, 277.18, 329.63].forEach((f, i) => {
      const o = ctx.createOscillator(), g = ctx.createGain();
      o.type = i % 2 ? 'sine' : 'triangle';
      o.frequency.value = f; o.detune.value = (Math.random() - .5) * 10;
      g.gain.value = .06 / (1 + i * .4);
      o.connect(g); g.connect(lp); o.start();
      const lfo = ctx.createOscillator(), lg = ctx.createGain();
      lfo.frequency.value = .04 + Math.random() * .08;
      lg.gain.value = .022 / (1 + i * .4);
      lfo.connect(lg); lg.connect(g.gain); lfo.start();
      sources.push(o, lfo);
    });
    const n = ctx.createBufferSource();
    n.buffer = noise(); n.loop = true;
    const bp = ctx.createBiquadFilter();
    bp.type = 'bandpass'; bp.frequency.value = 500; bp.Q.value = .7;
    const ng = ctx.createGain(); ng.gain.value = .04;
    n.connect(bp); bp.connect(ng); ng.connect(bus); n.start();
    sources.push(n);
    return {
      setBreath(l) {
        const t = ctx.currentTime;
        lp.frequency.setTargetAtTime(420 + l * 900, t, .25);
        bp.frequency.setTargetAtTime(340 + l * 520, t, .3);
        ng.gain.setTargetAtTime(.04 + l * .16, t, .25);
      },
      stop() {
        fadeBus(bus, 0, .8);
        setTimeout(() => { sources.forEach(s => { try { s.stop(); } catch (e) {} }); bus.disconnect(); }, 950);
      }
    };
  }

  function birds() {
    const bus = ctx.createGain();
    bus.gain.value = 0; bus.connect(out);
    fadeBus(bus, .8, 1.5);
    const n = ctx.createBufferSource();
    n.buffer = noise(); n.loop = true;
    const lp = ctx.createBiquadFilter();
    lp.type = 'lowpass'; lp.frequency.value = 850;
    const wind = ctx.createGain(); wind.gain.value = .05;
    n.connect(lp); lp.connect(wind); wind.connect(bus); n.start();
    let alive = true, timer = null;
    function chirp() {
      if (!alive) return;
      const t = ctx.currentTime + .02;
      const base = 2500 + Math.random() * 2300;
      const notes = 2 + Math.floor(Math.random() * 4);
      const g0 = ctx.createGain(), p = panner((Math.random() * 2 - 1) * .8);
      g0.gain.value = .035 + Math.random() * .05;
      g0.connect(p); p.connect(bus);
      let st = t;
      for (let i = 0; i < notes; i++) {
        const d = .05 + Math.random() * .07;
        const f1 = base * (.9 + Math.random() * .25), f2 = f1 * (Math.random() < .5 ? 1.35 : .72);
        const o = ctx.createOscillator(), g = ctx.createGain();
        o.type = 'sine';
        o.frequency.setValueAtTime(f1, st);
        o.frequency.exponentialRampToValueAtTime(f2, st + d);
        envelope(g, st, 1, .01, d);
        o.connect(g); g.connect(g0);
        o.start(st); o.stop(st + d + .02);
        st += d + .03 + Math.random() * .06;
      }
      timer = setTimeout(chirp, 700 + Math.random() * 2600);
    }
    timer = setTimeout(chirp, 500);
    return {
      setBreath(l) { wind.gain.setTargetAtTime(.03 + l * .08, ctx.currentTime, .3); lp.frequency.setTargetAtTime(650 + l * 500, ctx.currentTime, .3); },
      stop() {
        alive = false; clearTimeout(timer);
        fadeBus(bus, 0, .7);
        setTimeout(() => { try { n.stop(); } catch (e) {} bus.disconnect(); }, 850);
      }
    };
  }

  let whiteBuf = null;
  function white() {
    if (whiteBuf) return whiteBuf;
    const len = ctx.sampleRate * 3;
    whiteBuf = ctx.createBuffer(1, len, ctx.sampleRate);
    const d = whiteBuf.getChannelData(0);
    for (let i = 0; i < len; i++) d[i] = Math.random() * 2 - 1;
    return whiteBuf;
  }
  function loopSrc(buf) { const n = ctx.createBufferSource(); n.buffer = buf; n.loop = true; n.start(); return n; }
  function filt(type, f, q) { const b = ctx.createBiquadFilter(); b.type = type; b.frequency.value = f; if (q) b.Q.value = q; return b; }
  function chain(...nodes) { for (let i = 0; i < nodes.length - 1; i++) nodes[i].connect(nodes[i + 1]); return nodes[nodes.length - 1]; }

  function rain() {
    const bus = ctx.createGain(); bus.gain.value = 0; bus.connect(out); fadeBus(bus, .9, 2);
    const n = loopSrc(white()), g = ctx.createGain(); g.gain.value = .07;
    chain(n, filt('highpass', 900), filt('lowpass', 5200), g, bus);
    const drops = setInterval(() => {
      if (Math.random() < .35) return;
      const t = ctx.currentTime, s = ctx.createBufferSource(); s.buffer = white();
      const bp = filt('bandpass', 1800 + Math.random() * 3500, 6), dg = ctx.createGain(), p = panner(Math.random() * 1.6 - .8);
      envelope(dg, t, .05 + Math.random() * .05, .003, .05);
      chain(s, bp, dg, p, bus); s.start(t, Math.random() * 2, .08);
    }, 55);
    return {
      setBreath(l) { g.gain.setTargetAtTime(.06 + l * .03, ctx.currentTime, .4); },
      stop() { clearInterval(drops); fadeBus(bus, 0, .8); setTimeout(() => { try { n.stop(); } catch (e) {} bus.disconnect(); }, 950); }
    };
  }

  function forest() {
    const bus = ctx.createGain(); bus.gain.value = 0; bus.connect(out); fadeBus(bus, .9, 2.5);
    const w = ctx.createBufferSource(); w.buffer = noise(); w.loop = true; w.start();
    const wl = filt('lowpass', 520, .5), wg = ctx.createGain(); wg.gain.value = .16;
    chain(w, wl, wg, bus);
    const lfo = ctx.createOscillator(), lg = ctx.createGain(); lfo.frequency.value = .07; lg.gain.value = 180; lfo.connect(lg); lg.connect(wl.frequency); lfo.start();
    const r = loopSrc(white()), rg = ctx.createGain(); rg.gain.value = .012;
    chain(r, filt('bandpass', 3200, .8), rg, bus);
    const rl = ctx.createOscillator(), rlg = ctx.createGain(); rl.frequency.value = .23; rlg.gain.value = .01; rl.connect(rlg); rlg.connect(rg.gain); rl.start();
    let alive = true, followUp = null;
    const chirp = () => {
      if (!alive) return;
      const t = ctx.currentTime, o = ctx.createOscillator(), g = ctx.createGain(), p = panner(Math.random() * 1.4 - .7);
      const f = 2400 + Math.random() * 1400;
      o.frequency.setValueAtTime(f, t); o.frequency.exponentialRampToValueAtTime(f * 1.35, t + .07);
      envelope(g, t, .012, .01, .12); chain(o, g, p, bus); o.start(t); o.stop(t + .2);
    };
    const birdsT = setInterval(() => { if (Math.random() < .3) { chirp(); followUp = setTimeout(chirp, 140); } }, 2600);
    return {
      setBreath(l) { wg.gain.setTargetAtTime(.12 + l * .08, ctx.currentTime, .5); },
      stop() { alive = false; clearTimeout(followUp); clearInterval(birdsT); fadeBus(bus, 0, .8); setTimeout(() => { [w, r, lfo, rl].forEach(s => { try { s.stop(); } catch (e) {} }); bus.disconnect(); }, 950); }
    };
  }

  function breath() {
    const bus = ctx.createGain(); bus.gain.value = 0; bus.connect(out); fadeBus(bus, 1, .8);
    const n = loopSrc(white()), bp = filt('bandpass', 800, .9), g = ctx.createGain(); g.gain.value = 0;
    chain(n, bp, filt('lowpass', 2600), g, bus);
    let prev = null, pt = 0, flow = 0;
    return {
      setBreath(l) {
        const t = ctx.currentTime;
        if (prev !== null && t > pt) {
          const rate = (l - prev) / (t - pt);
          flow += (Math.min(1, Math.abs(rate) * 1.4) - flow) * .35;
          bp.frequency.setTargetAtTime(rate > 0 ? 1100 + l * 500 : 650 + l * 300, t, .15);
          g.gain.setTargetAtTime(flow * .32, t, .12);
        }
        prev = l; pt = t;
      },
      stop() { fadeBus(bus, 0, .5); setTimeout(() => { try { n.stop(); } catch (e) {} bus.disconnect(); }, 650); }
    };
  }

  return { ensure, relax, birds, rain, forest, breath, get ok() { return !!AC; } };
})();

  let ambient = null;
  let generation = 0;
  const modes = { birds: AudioKit.birds, relax: AudioKit.relax, rain: AudioKit.rain, forest: AudioKit.forest, breath: AudioKit.breath };
  function stop() {
    generation += 1;
    if (!ambient) return;
    const previous = ambient;
    ambient = null;
    try { previous.stop(); } catch (error) {}
  }
  async function start(mode) {
    stop();
    const request = generation;
    if (!Object.prototype.hasOwnProperty.call(modes, mode)) return false;
    try {
      const context = AudioKit.ensure();
      if (!context) return false;
      if (context.state !== 'running') await context.resume();
      if (request !== generation || context.state !== 'running') return false;
      ambient = modes[mode]();
      return true;
    } catch (error) {
      if (request === generation) stop();
      return false;
    }
  }
  function setBreath(level) {
    if (ambient) ambient.setBreath(Math.max(0, Math.min(1, level)));
  }
  return { start, stop, setBreath, get ok() { return AudioKit.ok; } };
})();
